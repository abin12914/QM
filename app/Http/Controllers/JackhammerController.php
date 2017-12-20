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
    public function registerAction(JackhammerRegistrationRequest $request)
    {
        $name                   = $request->get('name');
        $description            = $request->get('description');
        $contractorAccountId    = $request->get('contractor_account_id');
        //$rentalType             = $request->get('rent_type');
        //$rentPerDay             = $request->get('rate_daily');
        $rentPerFeet            = $request->get('rate_feet');

        $jackhammer = new Jackhammer;
        $jackhammer->name                   = $name;
        $jackhammer->description            = $description;
        $jackhammer->contractor_account_id  = $contractorAccountId;
        $jackhammer->rent_type              = "per_feet";//$rentalType;
        $jackhammer->rent_daily             = 0;//$rentPerDay;
        $jackhammer->rent_feet              = $rentPerFeet;
        $jackhammer->status                 = 1;
        if($jackhammer->save()) {
            return redirect()->back()->with("message","Successfully saved.")->with("alert-class","alert-success");
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the Jackhammer details. Try again after reloading the page!<small class='pull-right'> #10/01</small>")->with("alert-class","alert-danger");
        }
    }

    /**
     * Return view for jackhammer listing
     */
    public function jackhammerList(Request $request)
    {
        $accountId      = !empty($request->get('account_id')) ? $request->get('account_id') : 0;
        $jackhammerId   = !empty($request->get('jackhammer_id')) ? $request->get('jackhammer_id') : 0;

        $accounts = Account::where('type', 'personal')->where('status', '1')->get();
        $jackhammerCombobox = Jackhammer::where('status', '1')->get();

        $query = Jackhammer::where('status', '1');

        if(!empty($accountId) && $accountId != 0) {
            $query = $query->where('contractor_account_id', $accountId);
        }

        if(!empty($jackhammerId) && $jackhammerId != 0) {
            $query = $query->where('id', $jackhammerId);
        }

        $jackhammers = $query->with('account.accountDetail')->orderBy('created_at','desc')->paginate(15);

        return view('jackhammer.list',[
                'accounts'              => $accounts,
                'jackhammerCombobox'    => $jackhammerCombobox,
                'jackhammers'            => $jackhammers,
                'accountId'             => $accountId,
                'jackhammerId'           => $jackhammerId
            ]);
    }

    /**
     * Return contractor account for jackhammer id
     */
    public function getAccountByJackhammerId($jackhammerId)
    {
        $jackhammer = Jackhammer::where('id', $jackhammerId)->with('account')->first();
        if(!empty($jackhammer)) {
            $accountName = $jackhammer->account->account_name;
            $accountId   = $jackhammer->account->id;
            return ([
                    'flag'          => true,
                    'accountName'   => $accountName,
                    'accountId'     => $accountId
                ]);
        } else {
            return ([
                    'flag'          => false
                ]);
        }
    }
}
