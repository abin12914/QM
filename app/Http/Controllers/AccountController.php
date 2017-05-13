<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AccountRegistrationRequest;
use App\Models\AccountType;
use App\Models\Account;

class AccountController extends Controller
{
    /**
     * Return view for account registration
     */
    public function register()
    {
        $accountTypes = AccountType::all();
    	return view('account.register',[
            'accountTypes' => $accountTypes
        ]);
    }

     /**
     * Handle new account registration
     */
    public function registerAction(AccountRegistrationRequest $request)
    {
        $name               = $request->get('name');
        $description        = $request->get('description');
        $accountType        = $request->get('account_type');
        $financialStatus    = $request->get('financial_status');
        $openingBalance     = $request->get('opening_balance');

        $account = new Account;
        $account->name              = $name;
        $account->description       = $description;
        $account->type              = $accountType;
        $account->financial_status  = $financialStatus;
        $account->opening_balance   = $openingBalance;
        $account->status            = 1;
        if($account->save()) {
            return redirect()->back()->with("message","Account saved successfully.")->with("alert-class","alert-success");
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the account details. Try after reloading the page.")->with("alert-class","alert-danger");
        }
    }
}
