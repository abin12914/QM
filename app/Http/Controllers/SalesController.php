<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Account;
use App\Models\Product;

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
                'vehicles'  => $vehicles,
                'accounts'  => $accounts,
                'products'  => $products
            ]);
    }

     /**
     * Handle new account registration
     */
    public function registerAction(AccountRegistrationRequest $request)
    {
        $saveFlag = 0;
        
        $accountName        = $request->get('account_name');
        $description        = $request->get('description');
        $accountType        = $request->get('account_type');
        $financialStatus    = $request->get('financial_status');
        $openingBalance     = $request->get('opening_balance');
        $name               = $request->get('name');
        $phone              = $request->get('phone');
        $address            = $request->get('address');
        $relation           = $request->get('relation_type');

        $account = new Account;
        $account->account_name      = $accountName;
        $account->description       = $description;
        $account->type              = $accountType;
        $account->relation          = !empty($relation) ? $relation : $accountType;
        $account->financial_status  = $financialStatus;
        $account->opening_balance   = $openingBalance;
        $account->status            = 1;
        if($account->save()) {
            $accountDetails = new AccountDetail;
            $accountDetails->account_id = $account->id;
            // account type of real accounts does not need to store personal data
            if($accountType == 'personal') {
                $accountDetails->name       = $name;
                $accountDetails->phone      = $phone;
                $accountDetails->address    = $address;
            } else {
                $accountDetails->name       = $accountName;
            }
            $accountDetails->status     = 1;
            if($accountDetails->save()) {
                $saveFlag = 1;
            } else {
                $saveFlag = 0;  
            }
        } else {
            $saveFlag = 0;
        }

        if($saveFlag == 1) {
            return redirect()->back()->with("message","Account saved successfully.")->with("alert-class","alert-success");
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the account details. Try after reloading the page.")->with("alert-class","alert-danger");
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
}
