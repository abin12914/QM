<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\PurchasebleProduct;
use App\Models\Purchase;
use App\Models\Transaction;
use Auth;
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
    public function list()
    {
        $purchases = Purchase::paginate(15);
        if(!empty($purchases)) {
            return view('purchases.list',[
                    'purchases' => $purchases
                ]);
        } else {
            session()->flash('message', 'No purchase record available to show!');
            return view('purchases.list');
        }
    }
}
