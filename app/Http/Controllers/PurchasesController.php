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
        $purchases              = Purchase::orderBy('date_time', 'desc')->take(5)->get();

        return view('purchases.register',[
                'accounts'              => $accounts,
                'purchasebleproducts'   => $purchasebleProducts,
                'purchase_records'      => $purchases,
            ]);
    }

    /**
     * Handle new purchase registration
     */
    public function registerAction(PurchaseRegistrationRequest $request)
    {
        $transactionType    = $request->get('transaction_type');
        $supplierAccountId  = $request->get('supplier_account_id');
        $date               = $request->get('date');
        $time               = $request->get('time');
        $productId          = $request->get('product_id');
        $billNo             = $request->get('bill_no');
        $description        = $request->get('description');
        $billAmount         = $request->get('bill_amount');

        $purchaseAccount = Account::where('account_name','Purchases')->first();
        if($purchaseAccount) {
            $purchaseAccountId = $purchaseAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Purchase account not found.")->with("alert-class","alert-danger");
        }

        if($transactionType == 2) {
            $cashAccount = Account::where('account_name','Cash')->first();
            if($cashAccount) {
                $cashAccountId = $cashAccount->id;
            } else {
                return redirect()->back()->withInput()->with("message","Something went wrong! Cash account not found.!")->with("alert-class","alert-danger");
            }
        }

        //converting date and time to sql datetime format
        $dateTime = date('Y-m-d H:i:s', strtotime($date.' '.$time.':00'));

        $transaction = new Transaction;
        $transaction->debit_account_id  = $purchaseAccountId; //purchase account id
        $transaction->credit_account_id = $transactionType == 1 ? $supplierAccountId : $cashAccountId; //supplier
        $transaction->amount            = !empty($billAmount) ? $billAmount : '0';
        $transaction->date_time         = $dateTime;
        $transaction->particulars       = ($transactionType == 1 ? "Credit purchase" : "Cash purchase")." : ".$description;
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
                return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the purchase details. Try after reloading the page.")->with("alert-class","alert-danger");
            }
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the purchase details. Try after reloading the page.")->with("alert-class","alert-danger");
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
        

        if(!empty($accountId) && $accountId != 0) {
            $selectedAccount = Account::find($accountId);
            if(!empty($selectedAccount) && !empty($selectedAccount->id)) {
                $selectedAccountName = $selectedAccount->account_name;
            }
        } else {
            $selectedAccountName = '';
        }

        if(!empty($productId) && $productId != 0) {
            $selectedProduct = PurchasebleProduct::find($productId);
            if(!empty($selectedProduct) && !empty($selectedProduct->id)) {
                $selectedProductName = $selectedProduct->name;
            }
        } else {
            $selectedProductName = '';
        }

        $query = Purchase::where('status', 1);

        if(!empty($accountId) && $accountId != 0) {
            $query = $query->whereHas('transaction', function ($q) use($accountId) {
                $q->whereHas('creditAccount', function ($qry) use($accountId) {
                    $qry->where('id', $accountId);
                });
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

        $purchases = $query->with(['transaction.creditAccount'])->orderBy('date_time','desc')->paginate(10);
        
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
