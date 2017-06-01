<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Account;
use App\Models\Product;
use App\Models\Sale;

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

        return view('sales.register',[
                'vehicles'      => $vehicles,
                'accounts'      => $accounts,
                'products'      => $products,
                'sales_records' => []
            ]);
    }

     /**
     * Handle new account registration
     */
    public function registerAction()
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
    public function getByVehicleId($vehicleId)
    {
        $sale = Sale::where('vehicle_id',$vehicleId)->orderBy('created_at', 'desc')->first();
        return($sale);
    }
}
