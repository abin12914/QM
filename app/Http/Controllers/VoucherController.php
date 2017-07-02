<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use App\Models\Account;
use App\Models\Voucher;
use App\Models\Transaction;
use App\Http\Requests\CashVoucherRegistrationRequest;

class VoucherController extends Controller
{
    /**
     * Return view for daily statement registration
     */
    public function register()
    {
        $today = Carbon::now('Asia/Kolkata');
        
        $cashVouchers   = Voucher::orderBy('date_time', 'desc')->where('voucher_type','Cash')->take(5)->get();
        $dieselVouchers = Voucher::orderBy('date_time', 'desc')->where('voucher_type','Diesel')->take(5)->get();
        $accounts       = Account::where('type','personal')->get();

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
     * Handle new cash voucher registration
     */
    public function cashVoucherRegistrationAction(CashVoucherRegistrationRequest $request)
    {
        $date           = $request->get('cash_voucher_date');
        $time           = $request->get('cash_voucher_time');
        $accountId      = $request->get('cash_voucher_account_id');
        $voucherTransactionType = $request->get('cash_voucher_type');
        $voucherAmount  = $request->get('cash_voucher_amount');
        $description    = $request->get('cash_voucher_description');

        $cashAccount = Account::where('account_name','Cash')->first();
        if($cashAccount) {
            $cashAccountId = $cashAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Cash account not found.")->with("alert-class","alert-danger");
        }

        $account = Account::where('id',$accountId)->first();
        if($account) {
            $name = $account->accountDetail->name;
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Selected account not found.")->with("alert-class","alert-danger");
        }

        if($voucherTransactionType == 1) {
            $debitAccountId     = $cashAccountId;
            $creditAccountId    = $accountId;
            $particulars        = $description." :(Cash recieved from ".$name.")";
        } else {
            $debitAccountId     = $accountId;
            $creditAccountId    = $cashAccountId;
            $particulars        = $description." :(Cash paid to ".$name.")";
        }

        //converting date and time to sql datetime format
        $dateTime = date('Y-m-d H:i:s', strtotime($date.' '.$time.':00'));

        $transaction = new Transaction;
        $transaction->debit_account_id  = $debitAccountId;
        $transaction->credit_account_id = $creditAccountId;
        $transaction->amount            = !empty($voucherAmount) ? $voucherAmount : '0';
        $transaction->date_time         = $dateTime;
        $transaction->particulars       = $particulars;
        $transaction->status            = 1;
        $transaction->created_user_id   = Auth::user()->id;
        if($transaction->save()) {
            $voucher = new Voucher;
            $voucher->date_time        = $dateTime;
            $voucher->voucher_type     = 'Cash';
            $voucher->account_id       = $accountId;
            $voucher->transaction_type = $voucherTransactionType;
            $voucher->amount           = $voucherAmount;
            $voucher->description      = $description;
            $voucher->transaction_id   = $transaction->id;
            $voucher->status           = 1;
            
            if($voucher->save()) {
                return redirect()->back()->with("message","Successfully saved.")->with("alert-class","alert-success");
            } else {
                return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the voucher. Try after reloading the page.")->with("alert-class","alert-danger");
            }
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the voucher details. Try after reloading the page.")->with("alert-class","alert-danger");
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
