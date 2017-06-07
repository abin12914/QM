<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Account;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Transaction;
use Auth;
use App\Http\Requests\CreditSaleRegistrationRequest;
use App\Http\Requests\CashSaleRegistrationRequest;

class SalesController extends Controller
{
    /**
     * Return view for account registration
     */
    public function register()
    {
        $vehicles = Vehicle::get();
        $accounts = Account::get();
        $products = Product::get();
        $sales    = Sale::orderBy('date_time', 'desc')->get();

        return view('sales.register',[
                'vehicles'      => $vehicles,
                'accounts'      => $accounts,
                'products'      => $products,
                'sales_records' => $sales,
            ]);
    }

    /**
     * Handle new credit sale registration
     */
    public function creditSaleRegisterAction(CreditSaleRegistrationRequest $request)
    {
        $vehicleId          = $request->get('vehicle_id');
        $purchaserAccountId = $request->get('purchaser_account_id');
        $date               = $request->get('date');
        $time               = $request->get('time');
        $productId          = $request->get('product_id');
        $measureType        = $request->get('measure_type');
        $quantity           = $request->get('quantity');
        $rate               = $request->get('rate');
        $billAmount         = $request->get('bill_amount');
        $discount           = $request->get('discount');
        $deductedTotal      = $request->get('deducted_total');

        $salesAccount = Account::where('account_name','Sales')->first();
        if($salesAccount) {
            $salesAccountId = $salesAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Sales account not found.")->with("alert-class","alert-danger");
        }

        if(($quantity * $rate) != $billAmount) {
            return redirect()->back()->withInput()->with("message","Something went wrong! Bill calculation error.")->with("alert-class","alert-danger");
        }
        if(($billAmount - $discount) != $deductedTotal) {
            return redirect()->back()->withInput()->with("message","Something went wrong! Discount deduction error.")->with("alert-class","alert-danger");
        }

        //converting date and time to sql datetime format
        $dateTime = date('Y-m-d H:i:s', strtotime($date.' '.$time.':00'));

        $transaction = new Transaction;
        $transaction->debit_account_id  = $purchaserAccountId;
        $transaction->credit_account_id = $salesAccountId; //sale account id
        $transaction->amount            = !empty($deductedTotal) ? $deductedTotal : '0';
        $transaction->date_time         = $dateTime;
        $transaction->particulars       = "Credit sale";
        $transaction->status            = 1;
        $transaction->created_user_id   = Auth::user()->id;
        if($transaction->save()) {
            $sale = new Sale;
            $sale->transaction_id   = $transaction->id;
            $sale->vehicle_id       = $vehicleId;
            $sale->date_time        = $dateTime;
            $sale->product_id       = $productId;
            $sale->measure_type     = $measureType;
            if($measureType == 1) {
                $sale->quantity         = $quantity;
                $sale->rate             = $rate;
                $sale->discount         = $discount;
                $sale->total_amount     = $deductedTotal;
            } else {
                $sale->quantity         = 0;
                $sale->rate             = 0;
                $sale->discount         = 0;
                $sale->total_amount     = 0;
            }
            $sale->status           = 1;
            
            if($sale->save()) {
                return redirect()->back()->with("message","Sale saved successfully.")->with("alert-class","alert-success");
            } else {
                return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the sale details. Try after reloading the page.")->with("alert-class","alert-danger");
            }
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the sale details. Try after reloading the page.")->with("alert-class","alert-danger");
        }
    }

    /**
     * Handle new cash sale registration
     */
    public function cashSaleRegisterAction(CashSaleRegistrationRequest $request)
    {
        $vehicleId          = $request->get('vehicle_id_cash');
        $purchaserAccountId = $request->get('purchaser_account_id_cash');
        $date               = $request->get('date_cash');
        $time               = $request->get('time_cash');
        $productId          = $request->get('product_id_cash');
        $measureType        = $request->get('measure_type_cash');
        $quantity           = $request->get('quantity_cash');
        $rate               = $request->get('rate_cash');
        $billAmount         = $request->get('bill_amount_cash');
        $discount           = $request->get('discount_cash');
        $deductedTotal      = $request->get('deducted_total_cash');
        $oldBalance         = $request->get('old_balance');
        $total              = $request->get('total');
        $payment            = $request->get('paid_amount');
        $balance            = $request->get('balance');

        $cashAccount = Account::where('account_name','Cash')->first();
        if($cashAccount) {
            $cashAccountId = $cashAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Cash account not found.!")->with("alert-class","alert-danger");
        }

        $salesAccount = Account::where('account_name','Sales')->first();
        if($salesAccount) {
            $salesAccountId = $salesAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Sales account not found.!")->with("alert-class","alert-danger");
        }

        if(($quantity * $rate) != $billAmount) {
            return redirect()->back()->withInput()->with("message","Something went wrong! Bill calculation error.")->with("alert-class","alert-danger");
        }
        if(($billAmount - $discount) != $deductedTotal) {
            return redirect()->back()->withInput()->with("message","Something went wrong! Discount deduction error.")->with("alert-class","alert-danger");
        }
        if(($deductedTotal + $oldBalance) != $total) {
            return redirect()->back()->withInput()->with("message","Something went wrong! Total bill calculation error.")->with("alert-class","alert-danger");
        }
        if(($total - $payment) != $balance) {
            return redirect()->back()->withInput()->with("message","Something went wrong! Balance calculation error.")->with("alert-class","alert-danger");
        }

        //converting date and time to sql datetime format
        $dateTime = date('Y-m-d H:i:s', strtotime($date.' '.$time.':00'));

        $transaction = new Transaction;
        $transaction->debit_account_id  = $cashAccountId;
        $transaction->credit_account_id = $salesAccountId; //sale account id
        $transaction->amount            = $deductedTotal;
        $transaction->date_time         = $dateTime;
        $transaction->particulars       = "Cash sale";
        $transaction->status            = 1;
        $transaction->created_user_id   = Auth::user()->id;
        if($transaction->save()) {
            $sale = new Sale;
            $sale->transaction_id   = $transaction->id;
            $sale->vehicle_id       = $vehicleId;
            $sale->date_time        = $dateTime;
            $sale->product_id       = $productId;
            $sale->measure_type     = $measureType;
            $sale->quantity         = $quantity;
            $sale->rate             = $rate;
            $sale->discount         = $discount;
            $sale->total_amount     = $deductedTotal;
            $sale->status           = 1;
            
            if($sale->save()) {
                return redirect()->back()->with("message","Sale saved successfully.")->with("alert-class","alert-success");
            } else {
                return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the sale details. Try after reloading the page.")->with("alert-class","alert-danger");
            }
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the sale details. Try after reloading the page.")->with("alert-class","alert-danger");
        }
    }

    /**
     * Return view for account listing
     */
    public function list()
    {
        $accounts = Account::paginate(10);
        if(!empty($accounts)) {
            return view('account.list',[
                    'accounts' => $accounts
                ]);
        } else {
            session()->flash('message', 'No accounts available to show!');
            return view('account.list');/*->with("message","No account records available!")->with("alert-class","alert-success");*/
        }
    }

    /**
     * Return sales details for given vehicle id
     */
    public function getLastSaleByVehicleId($vehicleId)
    {
        $sale = Sale::where('vehicle_id',$vehicleId)->orderBy('created_at', 'desc')->first();
        
        if(!empty($sale)) {
            $productId          = $sale->product_id;
            $purchaserAccountId = $sale->transaction->debit_account_id;
            $measureType        = $sale->measure_type;

            return([
                    'flag'                  => true,
                    'productId'             => $productId,
                    'purchaserAccountId'    => $purchaserAccountId,
                    'measureType'           => $measureType
                ]);
        } else {
            return([
                    'flag' => false
                ]);
        }
    }
}
