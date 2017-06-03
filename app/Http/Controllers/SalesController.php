<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Account;
use App\Models\Product;
use App\Models\Sale;
use App\Http\Requests\SaleRegistrationRequest;

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
     * Handle new account registration
     */
    public function registerAction(SaleRegistrationRequest $request)
    {
        dd('x');
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
