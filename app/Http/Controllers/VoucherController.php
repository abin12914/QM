<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use App\Models\Account;
use App\Models\Voucher;
use App\Http\Requests\VoucherRegistrationRequest;

class VoucherController extends Controller
{
    /**
     * Return view for daily statement registration
     */
    public function register()
    {
        $today = Carbon::now('Asia/Kolkata');
        
        $cashVouchers   = Voucher::orderBy('date_time', 'desc')->where('voucher_type','cash')->take(5)->get();
        $dieselVouchers = Voucher::orderBy('date_time', 'desc')->where('voucher_type','diesel')->take(5)->get();
        $accounts       = Account::where('relation','employee')->where('type','personal')->get();

        if(!empty($accounts)) {
            return view('voucher.register',[
                    'today' => $today,
                    'accounts'          => $accounts,
                    'cashVouchers'      => $cashVouchers,
                    'dieselVouchers'    => $dieselVouchers,
                ]);
        } else {
            return view('voucher.register',[
                    'today' => $today
                ]);
        }
    }

    /**
     * Return account name for given account id
     */
    public function getAccountDetailsByaccountId($accountId)
    {
        $account = Account::where('id', $accountId)->first();
        if(!empty($account)) {
            return ([
                    'flag' => true,
                    'name' => $account->accountDetail->name,
                ]);
        } else {
            return ([
                    'flag'      => false
                ]);            
        }
    }
}
