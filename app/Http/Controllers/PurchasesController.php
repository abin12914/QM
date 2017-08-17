<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\PurchasebleProduct;
use App\Models\Purchase;
use App\Models\Transaction;
use Auth;
use DateTime;
use App\Http\Requests\PurchaseRegistrationRequest;

class PurchasesController extends Controller
{
    /**
     * Return view for purchase registration
     */
    public function register()
    {
        $accounts               = Account::where('type','personal')->get();
        $purchasebleProducts    = PurchasebleProduct::get();
        $purchases              = Purchase::with(['purchasebleProduct', 'transaction.creditAccount'])->orderBy('date_time', 'desc')->take(5)->get();

        return view('purchases.register',[
                'accounts'              => $accounts,
                'purchasebleProducts'   => $purchasebleProducts,
                'purchaseRecords'      => $purchases,
            ]);
    }

    /**
     * Handle new purchase registration
     */
    public function registerAction(PurchaseRegistrationRequest $request)
    {
        $tempDescription        = '';
        $transactionType        = $request->get('transaction_type');
        $supplierAccountId      = $request->get('supplier_account_id');
        $date                   = $request->get('date');
        $time                   = $request->get('time');
        $productId              = $request->get('product_id');
        $explosiveQuantityCap   = $request->get('explosive_quantity_cap');
        $explosiveQuantityGel   = $request->get('explosive_quantity_gel');
        $billNo                 = $request->get('bill_no');
        $description            = $request->get('description');
        $billAmount             = $request->get('bill_amount');

        $purchaseAccount = Account::where('account_name','Purchases')->first();
        if($purchaseAccount) {
            $purchaseAccountId = $purchaseAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the purchase details. Try again after reloading the page!<small class='pull-right'> #03/01</small>")->with("alert-class","alert-danger");
        }

        if($transactionType == 2) {
            $cashAccount = Account::where('account_name','Cash')->first();
            if($cashAccount) {
                $cashAccountId = $cashAccount->id;
            } else {
                return redirect()->back()->withInput()->with("message","Failed to save the purchase details. Try again after reloading the page!<small class='pull-right'> #03/02</small>")->with("alert-class","alert-danger");
            }
        }

        if($productId == 2) {
            $tempDescription = ("Cap : " . $explosiveQuantityCap . " Gel : " . $explosiveQuantityGel);
        }

        if($transactionType == 1) {
            $tempDescription = ($tempDescription . " - [Credit purchase] ");
        } else {
            $tempDescription = ($tempDescription . " - [Cash purchase] ");
        }

        //converting date and time to sql datetime format
        $dateTime = date('Y-m-d H:i:s', strtotime($date.' '.$time.':00'));

        $transaction = new Transaction;
        $transaction->debit_account_id  = $purchaseAccountId; //purchase account id
        $transaction->credit_account_id = $transactionType == 1 ? $supplierAccountId : $cashAccountId; //supplier
        $transaction->amount            = !empty($billAmount) ? $billAmount : '0';
        $transaction->date_time         = $dateTime;
        $transaction->particulars       = $tempDescription . $description;
        $transaction->status            = 1;
        $transaction->created_user_id   = Auth::user()->id;
        if($transaction->save()) {
            $purchase = new Purchase;
            $purchase->transaction_id   = $transaction->id;
            $purchase->date_time        = $dateTime;
            $purchase->product_id       = $productId;
            $purchase->bill_no          = $billNo;
            $purchase->bill_amount      = $billAmount;
            $purchase->status           = 1;
            
            if($purchase->save()) {
                return redirect()->back()->with("message","Successfully saved.")->with("alert-class","alert-success");
            } else {
                //delete the transaction if associated purchase saving failed.
                $transaction->delete();

                return redirect()->back()->withInput()->with("message","Failed to save the purchase details. Try again after reloading the page!<small class='pull-right'> #03/03</small>")->with("alert-class","alert-danger");
            }
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the purchase details. Try again after reloading the page!<small class='pull-right'> #03/04</small>")->with("alert-class","alert-danger");
        }
    }

    /**
     * Return view for account listing
     */
    public function list(Request $request)
    {
        $accountId      = !empty($request->get('account_id')) ? $request->get('account_id') : 0;
        $fromDate       = !empty($request->get('from_date')) ? $request->get('from_date') : '';
        $toDate         = !empty($request->get('to_date')) ? $request->get('to_date') : '';
        $productId      = !empty($request->get('product_id')) ? $request->get('product_id') : 0;

        $accounts       = Account::where('type', 'personal')->where('status', '1')->get();
        $cashAccount    = Account::find(1);
        $accounts->push($cashAccount); //attaching cash account to the accounts
        $products       = PurchasebleProduct::where('status', '1')->get();

        $query = Purchase::where('status', 1);

        if(!empty($accountId) && $accountId != 0) {
            $query = $query->whereHas('transaction', function ($qry) use($accountId) {
                $qry->where('credit_account_id', $accountId);
            });
        }

        if(!empty($productId) && $productId != 0) {
            $query = $query->where('product_id', $productId);
        }

        if(!empty($fromDate)) {
            $searchFromDate = new DateTime($fromDate);
            $searchFromDate = $searchFromDate->format('Y-m-d');
            $query = $query->where('date_time', '>=', $searchFromDate);
        }

        if(!empty($toDate)) {
            $searchToDate = new DateTime($toDate." 23:59");
            $searchToDate = $searchToDate->format('Y-m-d H:i');
            $query = $query->where('date_time', '<=', $searchToDate);
        }

        $purchases = $query->with(['transaction.creditAccount', 'purchasebleProduct'])->orderBy('date_time','desc')->paginate(10);
        
        return view('purchases.list',[
                'accounts'              => $accounts,
                'products'              => $products,
                'purchases'             => $purchases,
                'accountId'             => $accountId,
                'productId'             => $productId,
                'fromDate'              => $fromDate,
                'toDate'                => $toDate,
            ]);
    }
}
