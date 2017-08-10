<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AccountRegistrationRequest;
use App\Models\Account;
use App\Models\AccountDetail;
use App\Models\Transaction;
use DateTime;
use Auth;

class AccountController extends Controller
{
    /**
     * Return view for account registration
     */
    public function register()
    {
    	return view('account.register');
    }

     /**
     * Handle new account registration
     */
    public function registerAction(AccountRegistrationRequest $request)
    {
        $saveFlag = 0;
        
        $accountName        = $request->get('account_name');
        $description        = $request->get('description');
        $accountType        = $request->get('account_type');
        $financialStatus    = $request->get('financial_status');
        $openingBalance     = $request->get('opening_balance');
        $name               = $request->get('name');
        $phone              = $request->get('phone');
        $address            = $request->get('address');
        $relation           = $request->get('relation_type');

        $openingBalanceAccount = Account::where('account_name','Account Opening Balance')->first();
        if(!empty($openingBalanceAccount) && !empty($openingBalanceAccount->id)) {
            $openingBalanceAccountId = $openingBalanceAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the account details. Try again after reloading the page!<small class='pull-right'> #07/01</small>")->with("alert-class","alert-danger");
        }

        $account = new Account;
        $account->account_name      = $accountName;
        $account->description       = $description;
        $account->type              = $accountType;
        $account->relation          = !empty($relation) ? $relation : $accountType;
        $account->financial_status  = $financialStatus;
        $account->opening_balance   = $openingBalance;
        $account->status            = 1;
        if($account->save()) {
            $accountDetails = new AccountDetail;
            $accountDetails->account_id = $account->id;
            // account type of real/nominal accounts does not need to store personal data
            if($accountType == 'personal') {
                $accountDetails->name       = $name;
                $accountDetails->phone      = $phone;
                $accountDetails->address    = $address;
            } else {
                $accountDetails->name       = $accountName . " Account";
            }
            $accountDetails->status     = 1;
            if($accountDetails->save()) {
                if($financialStatus == 'debit') {//incoming [account holder gives cash to company] [Creditor]
                    $debitAccountId     = $openingBalanceAccountId;
                    $creditAccountId    = $account->id;
                    $particulars        = "Opening balance of ". $name . " - Debit [Creditor]";
                } else if($financialStatus == 'credit'){//outgoing [company gives cash to account holder] [Debitor]
                    $debitAccountId     = $account->id;
                    $creditAccountId    = $openingBalanceAccountId;
                    $particulars        = "Opening balance of ". $name . " - Credit [Debitor]";
                } else {
                    $debitAccountId     = $openingBalanceAccountId;
                    $creditAccountId    = $account->id;
                    $particulars        = "Opening balance of ". $name . " - None";
                }

                $dateTime = date('Y-m-d H:i:s', strtotime('now'));
                
                $transaction = new Transaction;
                $transaction->debit_account_id  = $debitAccountId;
                $transaction->credit_account_id = $creditAccountId;
                $transaction->amount            = !empty($openingBalance) ? $openingBalance : '0';
                $transaction->date_time         = $dateTime;
                $transaction->particulars       = $particulars;
                $transaction->status            = 1;
                $transaction->created_user_id   = Auth::user()->id;
                if($transaction->save()) {
                    $saveFlag = 1;
                } else {
                    //delete the account, account detail if opening balance transaction saving failed
                    $account->delete();
                    $accountDetails->delete();

                    $saveFlag = 2;
                }
            } else {
                //delete the account if account details saving failed
                $account->delete();

                $saveFlag = 3;
            }
        } else {
            $saveFlag = 4;
        }

        if($saveFlag == 1) {
            return redirect()->back()->with("message","Successfully saved.")->with("alert-class","alert-success");
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the account details. Try again after reloading the page!<small class='pull-right'> #07/02/". $saveFlag ."</small>")->with("alert-class","alert-danger");
        }

    }

    /**
     * Return view for account listing
     */
    public function list(Request $request)
    {
        $accountId  = !empty($request->get('account_id')) ? $request->get('account_id') : 0;
        $relation   = !empty($request->get('relation')) ? $request->get('relation') : '0';
        $type       = !empty($request->get('type')) ? $request->get('type') : '0';

        $accountsCombobox   = Account::where('status', '1')->get();

        $query = Account::where('status', '1');

        if(!empty($accountId) && $accountId != 0) {
            $query = $query->where('id', $accountId);
        }

        if(!empty($relation) && $relation != '0') {
            $query = $query->where('relation', $relation);
        }

        if(!empty($type) && $type != '0') {
            $query = $query->where('type', $type);
        }

        $accounts = $query->with('accountDetail')->orderBy('created_at','desc')->paginate(10);
        
        return view('account.list',[
                'accounts'          => $accounts,
                'accountsCombobox'  => $accountsCombobox,
                'accountId'         => $accountId,
                'relation'          => $relation,
                'type'              => $type
            ]);
    }

    /**
     * Return view for account statement
     */
    public function accountSatementSearch(Request $request)
    {
        /*$obDebitAmount  = 0;
        $obCreditAmount = 0;*/
        $totalDebit     = 0;
        $totalCredit    = 0;
        $accountId  = !empty($request->get('account_id')) ? $request->get('account_id') : 0;
        $fromDate   = !empty($request->get('from_date')) ? $request->get('from_date') : '';
        $toDate     = !empty($request->get('to_date')) ? $request->get('to_date') : '';

        $accounts = Account::where('type', 'personal')->where('status', '1')->get();
        if(empty($accounts)) {
            session()->flash('fixed-message', 'No accounts available to show!');
            return view('account-statement.statement');
        }

        if(!empty($accountId) && $accountId != 0) {
            $selectedAccount = Account::find($accountId);
            if(!empty($selectedAccount) && !empty($selectedAccount->id)) {
                $selectedAccountName = $selectedAccount->account_name;
            }
        } else {
            $selectedAccountName = '';
        }

        $totalDebit     = Transaction::where('debit_account_id', $accountId)->sum('amount');
        $totalCredit    = Transaction::where('credit_account_id', $accountId)->sum('amount');

        $query = Transaction::where(function ($qry) use($accountId) {
            $qry->where('debit_account_id', $accountId)->orWhere('credit_account_id', $accountId);
        });

        if(!empty($fromDate)) {
            $searchFromDate = new DateTime($fromDate);
            $searchFromDate = $searchFromDate->format('Y-m-d');
            $query = $query->where('date_time', '>=', $searchFromDate);
        }

        if(!empty($toDate)) {
            $searchToDate = new DateTime($toDate." 23:59");
            $searchToDate = $searchToDate->format('Y-m-d H:i');
            $query = $query->where('date_time', '<=', $searchToDate);
        }

        $transactions = $query->orderBy('date_time','desc')->paginate(10);
        
        return view('account-statement.statement',[
                'accounts'              => $accounts,
                'transactions'          => $transactions,
                'accountId'             => $accountId,
                'selectedAccountName'   => $selectedAccountName,
                'fromDate'              => $fromDate,
                'toDate'                => $toDate,
                'totalDebit'            => $totalDebit,
                'totalCredit'           => $totalCredit,
            ]);
    }
}
/*$oldBalanceDate = new DateTime($fromDate." 23:59:59");
$oldBalanceDate->modify('-1 day');
$oldBalanceDate = $oldBalanceDate->format('Y-m-d H:i:s');

$obTransactions = Transaction::where(function ($qry) use($accountId) {
    $qry->where('debit_account_id', $accountId)->orWhere('credit_account_id', $accountId);
})->where('date_time', '<=', $oldBalanceDate)->get();
if(!empty($obTransactions)) {
    foreach ($obTransactions as $key => $obtransaction) {
        if($obtransaction->debit_account_id == $accountId) {
            $obDebitAmount = $obDebitAmount + $obtransaction->amount;
        } else if($obtransaction->credit_account_id == $accountId) {
            $obCreditAmount = $obCreditAmount + $obtransaction->amount;
        }
    }
}*/