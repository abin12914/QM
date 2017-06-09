<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExcavatorRegistrationRequest;
use App\Models\Excavator;
use App\Models\Account;

class ExcavatorController extends Controller
{
    /**
     * Return view for excavator registration
     */
    public function register()
    {   
        $accounts = Account::where('type','personal')->orderBy('account_name')->get();
    	return view('excavator.register',[
            'accounts' => $accounts,
        ]);
    }

     /**
     * Handle new excavator registration
     */
    public function registerAction(ExcavatorRegistrationRequest $request)
    {
        $name                   = $request->get('name');
        $description            = $request->get('description');
        $contractorAccountId    = $request->get('contractor_account_id');
        $rentalType             = $request->get('rent_type');
        $rentMonthly            = $request->get('rate_monthly');
        $rentHourlyBucket       = $request->get('rate_bucket');
        $rentHourlyBreaker      = $request->get('rate_breaker');

        $excavator = new Excavator;
        $excavator->name                    = $name;
        $excavator->description             = $description;
        $excavator->contractor_account_id   = $contractorAccountId;
        $excavator->rent_type               = $rentalType;
        $excavator->rent_monthly            = $rentMonthly;
        $excavator->rent_hourly_bucket      = $rentHourlyBucket;
        $excavator->rent_hourly_breaker     = $rentHourlyBreaker;
        $excavator->status                  = 1;
        if($excavator->save()) {
            return redirect()->back()->with("message","Excavator details saved successfully.")->with("alert-class","alert-success");
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the excavator details. Try after reloading the page.")->with("alert-class","alert-danger");
        }
    }

    /**
     * Return view for excavators listing
     */
    public function list()
    {
        $excavators = Excavator::paginate(10);
        if(!empty($excavators)) {
            return view('excavator.list',[
                    'excavators' => $excavators
                ]);
        } else {
            session()->flash('message', 'No excavator records available to show!');
            return view('excavators.list');
        }
    }
}
