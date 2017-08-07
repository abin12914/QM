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
            return redirect()->back()->with("message","Successfully Saved.")->with("alert-class","alert-success");
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the excavator details. Try again after reloading the page!<small class='pull-right'> Error Code :09/01</small>")->with("alert-class","alert-danger");
        }
    }

    /**
     * Return view for excavators listing
     */
    public function list(Request $request)
    {
        $accountId      = !empty($request->get('account_id')) ? $request->get('account_id') : 0;
        $excavatorId    = !empty($request->get('excavator_id')) ? $request->get('excavator_id') : 0;

        $accounts = Account::where('type', 'personal')->where('status', '1')->get();
        $excavatorsCombobox = Excavator::where('status', '1')->get();

        $query = Excavator::where('status', '1');

        if(!empty($accountId) && $accountId != 0) {
            $selectedAccount = Account::find($accountId);
            if(!empty($selectedAccount) && !empty($selectedAccount->id)) {
                $query = $query->where('contractor_account_id', $accountId);
            } else {
                $accountId = 0;
            }
        }

        if(!empty($excavatorId) && $excavatorId != 0) {
            $selectedExcavator = Account::find($excavatorId);
            if(!empty($selectedExcavator) && !empty($selectedExcavator->id)) {
                $query = $query->where('id', $excavatorId);
            } else {
                $excavatorId = 0;
            }
        }

        $excavators = $query->with('account.accountDetail')->orderBy('created_at','desc')->paginate(10);

        return view('excavator.list',[
                'accounts'              => $accounts,
                'excavatorsCombobox'    => $excavatorsCombobox,
                'excavators'            => $excavators,
                'accountId'             => $accountId,
                'excavatorId'           => $excavatorId
            ]);
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
                    'flag' => false
                ]);
        }
    }
}
