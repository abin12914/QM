<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\JackhammerRegistrationRequest;
use App\Models\Jackhammer;
use App\Models\Account;

class JackhammerController extends Controller
{
    /**
     * Return view for jackhammer registration
     */
    public function register()
    {
        $accounts = Account::where('type','personal')->orderBy('account_name')->get();
    	return view('jackhammer.register',[
            'accounts' => $accounts
        ]);
    }

     /**
     * Handle new jackhammer registration
     */
    public function registerAction()
    {
        $name                   = $request->get('name');
        $description            = $request->get('description');
        $contractorAccountId    = $request->get('contractor_account_id');
        $rentalType             = $request->get('rent_type');
        $rentPerDay             = $request->get('rate_daily');
        $rentPerFeet            = $request->get('rate_feet');

        $jackhammer = new Jackhammer;
        $jackhammer->name                   = $name;
        $jackhammer->description            = $description;
        $jackhammer->contractor_account_id  = $contractorAccountId;
        $jackhammer->rent_type              = $rentalType;
        $jackhammer->rent_daily             = $rentPerDay;
        $jackhammer->rent_feet              = $rentPerFeet;
        $jackhammer->status                 = 1;
        if($jackhammer->save()) {
            return redirect()->back()->with("message","Jackhammer details saved successfully.")->with("alert-class","alert-success");
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the jackhammer details. Try after reloading the page.")->with("alert-class","alert-danger");
        }
    }
}
