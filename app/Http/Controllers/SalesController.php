<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Account;
use App\Models\Product;
use App\Models\Sale;
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
        $sales[]  = Sale::orderBy('created_at', 'desc')->first();

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

        $transaction = new Transaction;
        $transaction->debit_account_id  = $purchaserAccountId;
        $transaction->credit_account_id = 0;//sale account id
        $transaction->amount            = $deductedTotal;
        $transaction->date_time         = $date.' '.$time;
        $transaction->particulars       = "Credit sale";
        $transaction->status            = 1;
        $transaction->created_user_id   = Auth::user()->id;
        //save transaction

        $sale = new Sale;
        $sale->transaction_id   = 0;
        $sale->vehicle_id       = $vehicleId;
        $sale->date_time        = $date.' '.$time;
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
    }

    /**
     * Handle new cash sale registration
     */
    public function cashSaleRegisterAction(CashSaleRegistrationRequest $request)
    {
        dd('Y');
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
