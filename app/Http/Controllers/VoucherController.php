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
use App\Http\Requests\DeleteVoucherRequest;
use App\Models\Jackhammer;
use App\Models\Excavator;
class VoucherController extends Controller
{
    /**
     * Return view for daily statement registration
     */
    public function register()
    {
        $today = Carbon::now('Asia/Kolkata');
        
        $cashVouchers   = Voucher::where('status',1)->where('voucher_type','Cash')->with(['transaction.creditAccount'])->orderBy('created_at', 'desc')->take(5)->get();
        $creditVouchers = Voucher::where('status',1)->where('voucher_type','Credit')->with(['transaction.creditAccount', 'transaction.debitAccount'])->orderBy('created_at', 'desc')->take(5)->get();
        $machineVouchers = Voucher::where('status',1)->where('voucher_type','Credit_through')->with(['transaction.creditAccount', 'transaction.debitAccount'])->orderBy('created_at', 'desc')->take(5)->get();
        $excavators     = Excavator::where('status', 1)->with(['account'])->get();
        $jackhammers    = Jackhammer::where('status', 1)->with(['account'])->get();
        $accounts       = Account::where('type','personal')->get();

        return view('voucher.register',[
                'today' => $today,
                'accounts'          => $accounts,
                'cashVouchers'      => $cashVouchers,
                'creditVouchers'    => $creditVouchers,
                'machineVouchers'   => $machineVouchers,
                'excavators'        => $excavators,
                'jackhammers'       => $jackhammers
            ]);
    }

    /**
     * Handle new cash voucher registration
     */
    public function cashVoucherRegistrationAction(CashVoucherRegistrationRequest $request)
    {
        $date                   = $request->get('cash_voucher_date');
        $time                   = $request->get('cash_voucher_time');
        $accountId              = $request->get('cash_voucher_account_id');
        $voucherTransactionType = $request->get('cash_voucher_type');
        $voucherAmount          = $request->get('cash_voucher_amount');
        $description            = $request->get('cash_voucher_description');

        $cashAccount = Account::where('account_name','Cash')->first();
        if($cashAccount) {
            $cashAccountId = $cashAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the voucher details. Try again after reloading the page!<small class='pull-right'> #06/01</small>")->with("alert-class","alert-danger");
        }

        $account = Account::where('id',$accountId)->first();
        if($account) {
            $name = $account->accountDetail->name;
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the voucher details. Try again after reloading the page!<small class='pull-right'> #06/02</small>")->with("alert-class","alert-danger");
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
                //delete transaction if associated voucher record saving failed.
                $transaction->delete();

                return redirect()->back()->withInput()->with("message","Failed to save the voucher details. Try again after reloading the page!<small class='pull-right'> #06/03</small>")->with("alert-class","alert-danger");
            }
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the voucher details. Try again after reloading the page!<small class='pull-right'> #06/04</small>")->with("alert-class","alert-danger");
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
        $excavatorId        = $request->get('machine_voucher_excavator_id');
        $jackhammerId       = $request->get('machine_voucher_jackhammer_id');

        $debitAccount = Account::where('id', $debitAccountId)->first();
        if($debitAccount) {
            $debitAccountName = $debitAccount->accountDetail->name;
        } else{
            return redirect()->back()->withInput()->with("message","Failed to save the voucher details. Try again after reloading the page!<small class='pull-right'> #06/05</small>")->with("alert-class","alert-danger");

        }

        $creditAccount = Account::where('id', $creditAccountId)->first();
        if($creditAccount) {
            $creditAccountName = $creditAccount->accountDetail->name;
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the voucher details. Try again after reloading the page!<small class='pull-right'> #06/06</small>")->with("alert-class","alert-danger");
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
            if(!empty($excavatorId) || !empty($jackhammerId)) {
                $voucher->voucher_type     = 'Credit_through';
            } else {
                $voucher->voucher_type     = 'Credit';
            }
            $voucher->transaction_type = '3';
            $voucher->amount           = $voucherAmount;
            $voucher->description      = $description."[".$debitAccountName."->".$creditAccountName."]";
            $voucher->transaction_id   = $transaction->id;
            $voucher->excavator_id     = $excavatorId;
            $voucher->jackhammer_id    = $jackhammerId;
            $voucher->status           = 1;
            
            if($voucher->save()) {
                return redirect()->back()->with("message","Successfully saved.")->with("alert-class","alert-success");
            } else {
                //delete transaction if associated voucher record saving failed.
                $transaction->delete();

                return redirect()->back()->withInput()->with("message","Failed to save the voucher details. Try again after reloading the page!<small class='pull-right'> #06/07</small>")->with("alert-class","alert-danger");
            }
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the voucher details. Try again after reloading the page!<small class='pull-right'> #06/08</small>")->with("alert-class","alert-danger");
        }
    }

    /**
     * Return view for list voucher / cash
     */
    public function cashVoucherList(Request $request)
    {
        $totalDebitAmount   = 0;
        $totalCreditAmount  = 0;
        $accountId          = !empty($request->get('cash_voucher_account_id')) ? $request->get('cash_voucher_account_id') : 0;
        $transactionType    = !empty($request->get('transaction_type')) ? $request->get('transaction_type') : 0;
        $fromDate           = !empty($request->get('cash_voucher_from_date')) ? $request->get('cash_voucher_from_date') : '';
        $toDate             = !empty($request->get('cash_voucher_to_date')) ? $request->get('cash_voucher_to_date') : '';

        $accounts   = Account::where('type', 'personal')->where('status', '1')->get();

        $query = Voucher::where('status', 1)->where('voucher_type', 'Cash');

        if(!empty($accountId) && $accountId != 0) {
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
            $searchToDate = new DateTime($toDate." 23:59");
            $searchToDate = $searchToDate->format('Y-m-d H:i');
            $query = $query->where('date_time', '<=', $searchToDate);
        }

        $totalDebitQuery     = clone $query;
        $totalDebitAmount    = $totalDebitQuery->where('transaction_type', 1)->sum('amount');

        $totalCreditQuery     = clone $query;
        $totalCreditAmount    = $totalCreditQuery->where('transaction_type', 2)->sum('amount');

        $cashVouchers = $query->with(['transaction.debitAccount.accountDetail', 'transaction.creditAccount.accountDetail'])->orderBy('id','desc')->paginate(15);
        
        return view('voucher.list',[
                'accounts'          => $accounts,
                'cashVouchers'      => $cashVouchers,
                'accountId'         => $accountId,
                'transactionType'   => $transactionType,
                'fromDate'          => $fromDate,
                'toDate'            => $toDate,
                'creditVouchers'    => [],
                'machineVouchers'   => [],
                'totalDebitAmount'  => $totalDebitAmount,
                'totalCreditAmount' => $totalCreditAmount
            ]);
    }

    /**
     * Return view for list voucher / credit
     */
    public function creditVoucherList(Request $request)
    {
        $totalAmount        = 0;
        $accountId          = !empty($request->get('credit_voucher_account_id')) ? $request->get('credit_voucher_account_id') : 0;
        $fromDate           = !empty($request->get('credit_voucher_from_date')) ? $request->get('credit_voucher_from_date') : '';
        $toDate             = !empty($request->get('credit_voucher_to_date')) ? $request->get('credit_voucher_to_date') : '';

        $accounts   = Account::where('type', 'personal')->where('status', '1')->get();

        $query = Voucher::where('status', 1)->where('voucher_type', 'Credit');

        if(!empty($accountId) && $accountId != 0) {
            $query = $query->whereHas('transaction', function ($q) use($accountId) {
                $q->where('credit_account_id', $accountId)->orWhere('debit_account_id', $accountId);
            });
        }

        if(!empty($fromDate)) {
            $searchFromDate = new DateTime($fromDate);
            $searchFromDate = $searchFromDate->format('Y-m-d H:i');
            $query = $query->where('date_time', '>=', $searchFromDate);
        }

        if(!empty($toDate)) {
            $searchToDate = new DateTime($toDate." 23:59");
            $searchToDate = $searchToDate->format('Y-m-d H:i');
            $query = $query->where('date_time', '<=', $searchToDate);
        }

        $totalQuery     = clone $query;
        $totalAmount    = $totalQuery->sum('amount');

        $creditVouchers = $query->with(['transaction.debitAccount.accountDetail', 'transaction.creditAccount.accountDetail'])->orderBy('id','desc')->paginate(15);
        
        return view('voucher.list',[
                'accounts'        => $accounts,
                'creditVouchers'  => $creditVouchers,
                'accountId'       => $accountId,
                'fromDate'        => $fromDate,
                'toDate'          => $toDate,
                'cashVouchers'    => [],
                'machineVouchers' => [],
                'totalAmount'     => $totalAmount
            ]);
    }

    /**
     * Return view for list voucher / credit through
     */
    public function machineThroughVoucherList(Request $request)
    {
        $totalAmount        = 0;
        $accountId          = !empty($request->get('account_id')) ? $request->get('account_id') : 0;
        $excavatorId        = !empty($request->get('excavator_id')) ? $request->get('excavator_id') : 0;
        $jackhammerId       = !empty($request->get('jackhammer_id')) ? $request->get('jackhammer_id') : 0;
        $fromDate           = !empty($request->get('from_date')) ? $request->get('from_date') : '';
        $toDate             = !empty($request->get('to_date')) ? $request->get('to_date') : '';
        $machineClass       = !empty($request->get('machine_class')) ? $request->get('machine_class') : 0;

        $accounts       = Account::where('type', 'personal')->where('status', '1')->get();
        $excavators     = Excavator::where('status', '1')->get();
        $jackhammers    = Jackhammer::where('status', '1')->get();

        $query = Voucher::where('status', 1)->where('voucher_type', 'Credit_through');

        if(!empty($accountId) && $accountId != 0) {
            $query = $query->whereHas('transaction', function ($q) use($accountId) {
                $q->where('credit_account_id', $accountId)->orWhere('debit_account_id', $accountId);
            });
        }

        if(!empty($fromDate)) {
            $searchFromDate = new DateTime($fromDate);
            $searchFromDate = $searchFromDate->format('Y-m-d H:i');
            $query = $query->where('date_time', '>=', $searchFromDate);
        }

        if(!empty($toDate)) {
            $searchToDate = new DateTime($toDate." 23:59");
            $searchToDate = $searchToDate->format('Y-m-d H:i');
            $query = $query->where('date_time', '<=', $searchToDate);
        }

        if(!empty($machineClass)) {
            if($machineClass == 1) {                
                $query = $query->whereNotNull('excavator_id');
            } elseif($machineClass == 2) {
                $query = $query->whereNotNull('jackhammer_id');
            }
        }

        if(!empty($excavatorId)) {
            $query = $query->where('excavator_id', $excavatorId);
        }

        if(!empty($jackhammerId)) {
            $query = $query->where('jackhammer_id', $jackhammerId);
        }

        $totalQuery     = clone $query;
        $totalAmount    = $totalQuery->sum('amount');

        $machineVouchers = $query->with(['transaction.debitAccount.accountDetail', 'transaction.creditAccount.accountDetail', 'excavator', 'jackhammer'])->orderBy('id','desc')->paginate(25);
        
        return view('voucher.list',[
                'accounts'        => $accounts,
                'excavators'      => $excavators,
                'jackhammers'     => $jackhammers,
                'machineVouchers' => $machineVouchers,
                'accountId'       => $accountId,
                'excavatorId'     => $excavatorId,
                'jackhammerId'    => $jackhammerId,
                'fromDate'        => $fromDate,
                'toDate'          => $toDate,
                'cashVouchers'    => [],
                'creditVouchers'  => [],
                'totalAmount'     => $totalAmount
            ]);
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
     * Handle voucher delete action
     */
    public function deleteAction(DeleteVoucherRequest $request)
    {
        $voucherId = $request->get('voucher_id');
        $date       = $request->get('date');
        
        $voucher   = Voucher::where('id', $voucherId)->where('status', 1)->first();

        if(!empty($voucher) && !empty($voucher->id)) {
            if(Carbon::parse($voucher->date_time)->format('d-m-Y') != $date) {
                return redirect()->back()->with("message","Deletion restricted. Date change detected!! #06/09")->with("alert-class","alert-danger");
            }

            if($voucher->transaction->created_user_id != Auth::id() && Auth::user()->role != 'admin') {
                return redirect()->back()->with("message","Failed to delete the voucher details.You don't have the permission to delete this record! #06/10")->with("alert-class","alert-danger");
            }
            if($voucher->created_at->diffInDays(Carbon::now(), false) > 5) {
                return redirect()->back()->with("message","Deletion restricted.Only records created within 5 days can be deleted! #06/11")->with("alert-class","alert-danger");
            }

            $voucherTransactionDelete  = $voucher->transaction->delete();
            $voucherDeleteFlag         = $voucher->delete();

            if($voucherTransactionDelete && $voucherDeleteFlag) {
                return redirect()->back()->with("message","#". $voucher->transaction->id. " -Successfully deleted.")->with("alert-class","alert-success");
            }
        }

        return redirect()->back()->with("message","Failed to delete the voucher details.Try again after reloading the page! #0/12")->with("alert-class","alert-danger");
    }
}
