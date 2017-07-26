<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DateTime;
use App\Models\Account;
use App\Models\Voucher;
use App\Models\Transaction;
use App\Http\Requests\CashVoucherRegistrationRequest;
use App\Http\Requests\CreditVoucherRegistrationRequest;

class VoucherController extends Controller
{
    /**
     * Return view for daily statement registration
     */
    public function register()
    {
        $today = Carbon::now('Asia/Kolkata');
        
        $cashVouchers   = Voucher::orderBy('date_time', 'desc')->where('voucher_type','Cash')->take(5)->get();
        $creditVouchers = Voucher::orderBy('date_time', 'desc')->where('voucher_type','Credit')->take(5)->get();
        $accounts       = Account::where('type','personal')->get();

        if(!empty($accounts)) {
            return view('voucher.register',[
                    'today' => $today,
                    'accounts'          => $accounts,
                    'cashVouchers'      => $cashVouchers,
                    'creditVouchers'    => $creditVouchers,
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
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the voucher. Try after reloading the page.")->with("alert-class","alert-danger");
        }
    }

    /**
     * Handle new credit voucher registration
     */
    public function creditVoucherRegistrationAction(CreditVoucherRegistrationRequest $request)
    {
        $date               = $request->get('credit_voucher_date');
        $time               = $request->get('credit_voucher_time');
        $debitAccountId     = $request->get('credit_voucher_debit_account_id');
        $creditAccountId    = $request->get('credit_voucher_credit_account_id');
        $voucherAmount      = $request->get('credit_voucher_amount');
        $description        = $request->get('credit_voucher_description');

        $debitAccount = Account::where('id', $debitAccountId)->first();
        if($debitAccount) {
            $debitAccountName = $debitAccount->accountDetail->name;
        } else{
            return redirect()->back()->withInput()->with("message","Something went wrong! Selected debit account not found.")->with("alert-class","alert-danger");
        }

        $creditAccount = Account::where('id', $creditAccountId)->first();
        if($creditAccount) {
            $creditAccountName = $creditAccount->accountDetail->name;
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Selected credit account not found.")->with("alert-class","alert-danger");
        }

        //converting date and time to sql datetime format
        $dateTime = date('Y-m-d H:i:s', strtotime($date.' '.$time.':00'));

        $transaction = new Transaction;
        $transaction->debit_account_id  = $creditAccountId; //the crediter gives to company
        $transaction->credit_account_id = $debitAccountId; //the company gives to debiter
        $transaction->amount            = !empty($voucherAmount) ? $voucherAmount : '0';
        $transaction->date_time         = $dateTime;
        $transaction->particulars       = $description."[".$debitAccountName."->".$creditAccountName."]";
        $transaction->status            = 1;
        $transaction->created_user_id   = Auth::user()->id;
        if($transaction->save()) {
            $voucher = new Voucher;
            $voucher->date_time        = $dateTime;
            $voucher->voucher_type     = 'Credit';
            $voucher->transaction_type = '3';
            $voucher->amount           = $voucherAmount;
            $voucher->description      = $description."[".$debitAccountName."->".$creditAccountName."]";
            $voucher->transaction_id   = $transaction->id;
            $voucher->status           = 1;
            
            if($voucher->save()) {
                return redirect()->back()->with("message","Successfully saved.")->with("alert-class","alert-success");
            } else {
                return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the voucher. Try after reloading the page.")->with("alert-class","alert-danger");
            }
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the voucher. Try after reloading the page.")->with("alert-class","alert-danger");
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

    /**
     * Return view for list voucher / cash
     */
    public function cashVoucherList(Request $request)
    {
        $accountId          = !empty($request->get('cash_voucher_account_id')) ? $request->get('cash_voucher_account_id') : 0;
        $transactionType    = !empty($request->get('transaction_type')) ? $request->get('transaction_type') : 0;
        $fromDate           = !empty($request->get('cash_voucher_from_date')) ? $request->get('cash_voucher_from_date') : '';
        $toDate             = !empty($request->get('cash_voucher_to_date')) ? $request->get('cash_voucher_to_date') : '';

        $accounts   = Account::where('type', 'personal')->where('status', '1')->get();

        $query = Voucher::where('status', 1)->where('voucher_type', 'Cash');

        if(!empty($accountId) && $accountId != 0) {
            $selectedAccount = Account::find($accountId);
            if(!empty($selectedAccount) && !empty($selectedAccount->id)) {
                $selectedAccountName = $selectedAccount->account_name;

                /*$query = $query->whereHas('transaction', function ($q) use($accountId) {
                    $q->whereHas('creditAccount', function ($qry) use($accountId) {
                        $qry->where('id', $accountId);
                    })->orWhereHas('debitAccount', function ($qry) use($accountId) {
                        $qry->where('id', $accountId);
                    });
                });*/
                $query = $query->whereHas('transaction', function ($q) use($accountId) {
                    $q->where('credit_account_id', $accountId)->orWhere('debit_account_id', $accountId);
                });
            } else {
                $accountId = 0;
            }
        } else {
            $selectedAccountName = '';
        }

        if(!empty($transactionType) && $transactionType != 0) {
            $query = $query->where('transaction_type', $transactionType);
        }

        if(!empty($fromDate)) {
            $searchFromDate = new DateTime($fromDate);
            $searchFromDate = $searchFromDate->format('Y-m-d H:i');
            $query = $query->where('date_time', '>=', $searchFromDate);
        }

        if(!empty($toDate)) {
            $searchToDate = new DateTime($toDate);
            $searchToDate = $searchToDate->format('Y-m-d H:i');
            $query = $query->where('date_time', '<=', $searchToDate);
        }

        $cashVouchers = $query->with(['transaction.debitAccount.accountDetail', 'transaction.creditAccount.accountDetail'])->orderBy('date_time','desc')->paginate(10);
        
        return view('voucher.list',[
                'accounts'        => $accounts,
                'cashVouchers'    => $cashVouchers,
                'accountId'       => $accountId,
                'transactionType' => $transactionType,
                'fromDate'        => $fromDate,
                'toDate'          => $toDate,
                'creditVouchers'  => []
            ]);
    }

    /**
     * Return view for list voucher / credit
     */
    public function creditVoucherList(Request $request)
    {
        $accountId          = !empty($request->get('credit_voucher_account_id')) ? $request->get('credit_voucher_account_id') : 0;
        $fromDate           = !empty($request->get('credit_voucher_from_date')) ? $request->get('credit_voucher_from_date') : '';
        $toDate             = !empty($request->get('credit_voucher_to_date')) ? $request->get('credit_voucher_to_date') : '';

        $accounts   = Account::where('type', 'personal')->where('status', '1')->get();

        $query = Voucher::where('status', 1)->where('voucher_type', 'Credit');

        if(!empty($accountId) && $accountId != 0) {
            $selectedAccount = Account::find($accountId);
            if(!empty($selectedAccount) && !empty($selectedAccount->id)) {
                $selectedAccountName = $selectedAccount->account_name;

                $query = $query->whereHas('transaction', function ($q) use($accountId) {
                    $q->where('credit_account_id', $accountId)->orWhere('debit_account_id', $accountId);
                });
            } else {
                $accountId = 0;
            }
        } else {
            $selectedAccountName = '';
        }

        if(!empty($fromDate)) {
            $searchFromDate = new DateTime($fromDate);
            $searchFromDate = $searchFromDate->format('Y-m-d H:i');
            $query = $query->where('date_time', '>=', $searchFromDate);
        }

        if(!empty($toDate)) {
            $searchToDate = new DateTime($toDate);
            $searchToDate = $searchToDate->format('Y-m-d H:i');
            $query = $query->where('date_time', '<=', $searchToDate);
        }

        $creditVouchers = $query->with(['transaction.debitAccount.accountDetail', 'transaction.creditAccount.accountDetail'])->orderBy('date_time','desc')->paginate(10);
        
        return view('voucher.list',[
                'accounts'        => $accounts,
                'creditVouchers'  => $creditVouchers,
                'accountId'       => $accountId,
                'fromDate'        => $fromDate,
                'toDate'          => $toDate,
                'cashVouchers'    => []
            ]);
    }
}
