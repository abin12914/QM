<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExcavatorRegistrationRequest;
use App\Models\Excavator;
use App\Models\Account;
use App\Models\ExcavatorRent;
use DateTime;

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
        $rentMonthly            = ($rentalType == "monthly") ? ($request->get('rate_monthly')) : 0;
        $rentHourlyBucket       = ($rentalType == "hourly") ? ($request->get('rate_bucket')) : 0;
        $rentHourlyBreaker      = ($rentalType == "hourly") ? ($request->get('rate_breaker')) : 0;

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

    /**
     * Return contractor account for excavator id
     */
    public function getAccountByExcavatorId($excavatorId)
    {
        $excavator = Excavator::where('id', $excavatorId)->first();
        if(!empty($excavatorId) && !empty($excavator->id)) {
            $accountName = $excavator->account->account_name;
            $excavatorRent = ExcavatorRent::where('excavator_id', $excavatorId)->orderBy('to_date','desc')->first();
            if(!empty($excavatorRent) && !empty($excavatorRent->id)) {
                $excavatorLastRentDate = $excavatorRent->to_date;
                $excavatorLastRentDate = new DateTime($excavatorLastRentDate);
                $excavatorLastRentDate->modify('+1 day');
                $excavatorLastRentDate = $excavatorLastRentDate->format('m-d-Y');
            }

            return ([
                    'flag'          => true,
                    'accountName'   => $accountName,
                    'rent'          => ($excavator->rent_type == 'monthly') ? $excavator->rent_monthly : 0,
                    'excavatorLastRentDate' => !empty($excavatorLastRentDate) ? $excavatorLastRentDate : ''
                ]);
        } else {
            return ([
                    'flag'          => false
                ]);
        }
    }
}
