<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AccountRegistrationRequest;
use App\Models\Account;
use App\Models\AccountDetail;
use App\Models\Transaction;
use DateTime;
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
            // account type of real accounts does not need to store personal data
            if($accountType == 'personal') {
                $accountDetails->name       = $name;
                $accountDetails->phone      = $phone;
                $accountDetails->address    = $address;
            } else {
                $accountDetails->name       = $accountName;
            }
            $accountDetails->status     = 1;
            if($accountDetails->save()) {
                $saveFlag = 1;
            } else {
                $saveFlag = 0;  
            }
        } else {
            $saveFlag = 0;
        }

        if($saveFlag == 1) {
            return redirect()->back()->with("message","Account saved successfully.")->with("alert-class","alert-success");
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the account details. Try after reloading the page.")->with("alert-class","alert-danger");
        }

    }

    /**
     * Return view for account listing
     */
    public function list()
    {
        $accounts = Account::paginate(10);
        if(!empty($accounts)) {
            return view('account.list',[
                    'accounts' => $accounts
                ]);
        } else {
            session()->flash('message', 'No accounts available to show!');
            return view('account.list');/*->with("message","No account records available!")->with("alert-class","alert-success");*/
        }
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
            session()->flash('message', 'No accounts available to show!');
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

        $transactions = $query->orderBy('date_time','desc')->paginate(3);
        
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