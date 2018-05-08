<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AccountRegistrationRequest;
use App\Http\Requests\AccountUpdationRequest;
use App\Models\Account;
use App\Models\AccountDetail;
use App\Models\Transaction;
use App\Models\ProfitLoss;
use App\Models\VehicleType;
use App\Models\Sale;
use App\Models\Owner;
use \Carbon\Carbon;
use Validator;
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
    public function accountList(Request $request)
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

        $accounts = $query->with('accountDetail')->orderBy('created_at','desc')->paginate(15);
        
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
        $obDebitAmount  = 0;
        $obCreditAmount = 0;
        $subtotalDebit  = 0;
        $subtotalCredit = 0;
        $totalDebit     = 0;
        $totalCredit    = 0;
        $totalOverviewDebit = 0;
        $totalOverviewCredit = 0;
        $totalDebitAmount   = 0;
        $totalCreditAmount  = 0;
        $accountId  = !empty($request->get('account_id')) ? $request->get('account_id') : 0;
        $fromDate   = !empty($request->get('from_date')) ? $request->get('from_date') : '';
        $toDate     = !empty($request->get('to_date')) ? $request->get('to_date') : '';

        $accounts = Account::where('type', 'personal')->where('status', '1')->get();
        $cashAccount    = Account::find(1);
        $accounts->push($cashAccount); //attaching cash account to the accounts
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
            $totalDebitAmount       = Transaction::whereHas('debitAccount', function ($qry) {
                    $qry->where('type', 'personal');
                })->sum('amount');

            $totalCreditAmount       = Transaction::whereHas('creditAccount', function ($qry) {
                    $qry->where('type', 'personal');
                })->sum('amount');
        }

        $totalOverviewDebit     = Transaction::where('debit_account_id', $accountId)->sum('amount');
        $totalOverviewCredit    = Transaction::where('credit_account_id', $accountId)->sum('amount');

        $query = Transaction::where('status', 1);

        $query = $query->where(function ($qry) use($accountId) {
            $qry->where('debit_account_id', $accountId)->orWhere('credit_account_id', $accountId);
        });

        if(!empty($fromDate)) {
            $searchFromDate = new DateTime($fromDate);
            $searchFromDate = $searchFromDate->format('Y-m-d');
            $query = $query->where('date_time', '>=', $searchFromDate);

            $obDebitAmount  = Transaction::where('debit_account_id', $accountId)->where('date_time', '<', $searchFromDate)->sum('amount');
            $obCreditAmount = Transaction::where('credit_account_id', $accountId)->where('date_time', '<', $searchFromDate)->sum('amount');
        }

        if(!empty($toDate)) {
            $searchToDate = new DateTime($toDate." 23:59");
            $searchToDate = $searchToDate->format('Y-m-d H:i');
            $query = $query->where('date_time', '<=', $searchToDate);
        }

        $subtotalDebitQuery = clone $query;
        $subtotalDebit = $subtotalDebitQuery->where('debit_account_id', $accountId)->sum('amount');

        $subtotalCreditQuery = clone $query;
        $subtotalCredit = $subtotalCreditQuery->where('credit_account_id', $accountId)->sum('amount');

        $totalDebit     = $obDebitAmount + $subtotalDebit;
        $totalCredit    = $obCreditAmount + $subtotalCredit;

        $transactions = $query->orderBy('id','desc')->paginate(15);

        return view('account-statement.statement',[
                'accounts'              => $accounts,
                'transactions'          => $transactions,
                'accountId'             => $accountId,
                'selectedAccountName'   => $selectedAccountName,
                'fromDate'              => $fromDate,
                'toDate'                => $toDate,
                'totalOverviewDebit'    => $totalOverviewDebit,
                'totalOverviewCredit'   => $totalOverviewCredit,
                'totalDebit'            => $totalDebit,
                'totalCredit'           => $totalCredit,
                'subtotalDebit'         => $subtotalDebit,
                'subtotalCredit'        => $subtotalCredit,
                'obDebitAmount'         => $obDebitAmount,
                'obCreditAmount'        => $obCreditAmount,
                'totalDebitAmount'      => $totalDebitAmount,
                'totalCreditAmount'     => $totalCreditAmount
            ]);
    }

    /**
     * Return view for account statement list
     */
    public function creditList(Request $request)
    {
        $creditAmount       = [];
        $debitAmount        = [];
        $accounts           = [];
        $totalCredit        = 0;
        $totalDebit         = 0;

        $accountRelation    = $request->get('relation');

        if(!empty($accountRelation)) {
            $accounts = Account::with('accountDetail')->where('type', 'personal')->Where('relation', $accountRelation)->where('status', '1')->get();

            if(empty($accounts)) {
                session()->flash('fixed-message', 'No accounts available to show!');
                return view('account-statement.creditList');
            }

            $debitQuery = Transaction::whereHas('debitAccount', function ($qry) use($accountRelation) {
                        $qry->where('type', 'personal')->Where('relation', $accountRelation);
                    })->where('status', '1');

            $creditQuery = Transaction::whereHas('creditAccount', function ($qry) use($accountRelation) {
                        $qry->where('type', 'personal')->Where('relation', $accountRelation);
                    })->where('status', '1');


            $debitTransactions = $debitQuery->orderBy('id','desc')->get();

            $creditTransactions = $creditQuery->orderBy('id','desc')->get();

            foreach ($debitTransactions as $key => $transaction) {
                if(empty($debitAmount[$transaction->debit_account_id])) {
                    $debitAmount[$transaction->debit_account_id] = 0;
                }
                    
                $debitAmount[$transaction->debit_account_id] = $debitAmount[$transaction->debit_account_id] + $transaction->amount;
            }

            foreach ($creditTransactions as $key => $transaction) {
                if(empty($creditAmount[$transaction->credit_account_id])) {
                    $creditAmount[$transaction->credit_account_id] = 0;
                }
                
                $creditAmount[$transaction->credit_account_id] = $creditAmount[$transaction->credit_account_id] + $transaction->amount;
            }

            foreach ($accounts as $key => $account) {
                if(empty($debitAmount[$account->id])) {
                    $debitAmount[$account->id] = 0;
                }
                if(empty($creditAmount[$account->id])) {
                    $creditAmount[$account->id] = 0;
                }

                if($debitAmount[$account->id] > $creditAmount[$account->id]) {
                    $totalDebit = $totalDebit + ($debitAmount[$account->id] - $creditAmount[$account->id]);
                } else {
                    $totalCredit = $totalCredit + ($creditAmount[$account->id] - $debitAmount[$account->id]);
                }
            }
        }

        return view('account-statement.creditList',[
                'accounts'          => $accounts,
                'creditAmount'      => $creditAmount,
                'debitAmount'       => $debitAmount,
                'relation'          => $accountRelation,
                'totalCreditAmount' => $totalCredit,
                'totalDebitAmount'  => $totalDebit
            ]);
    }

    /**
     * Return view for profit & loss generation
     */
    public function profitLoss(Request $request)
    {
        $finalDebit         = 0;
        $finalCredit        = 0;
        $restrictedDate     = "";
        $shareConfirmButton = 0;
        $vehicleTypes       = [];
        $totalSaleCount     = 0;
        $salesCount         = 0;
        $saleProfitAmount   = 0;
        $totalSaleProfitAmount  = 0;
        $ratePerFeet        = 0;
        $owners             = [];
        $ownerShare         = [];
        $balanceAmount      = 0;

        $validator = Validator::make($request->all(), [
            "from_date" => "required|date_format:d-m-Y",
            "to_date"   => "required|date_format:d-m-Y|after:from_date",
        ]);

        if ($validator->fails()) 
        {
            return redirect(route('daily-statement-list-search'))->with("message", "Unable to show share options. Invalid date format!")->with("alert-class", "alert-danger");
        }

        $fromDate   = Carbon::createFromFormat('d-m-Y H:i:s', $request->get("from_date"). "00:00:00");
        $toDate     = Carbon::createFromFormat('d-m-Y H:i:s', $request->get("to_date"). "23:59:59");

        $transactionQuery = Transaction::where('status', 1)->whereBetween('date_time', [$fromDate, $toDate]);

        $totalCreditQuery = clone $transactionQuery;
        $totalCredit = $totalCreditQuery->where('credit_account_id', 2)->sum('amount');

        $totalDebitQuery = clone $transactionQuery;
        $totalDebit = $totalDebitQuery->whereIn('debit_account_id', [3, 4, 5, 6, 7, 8, 9])->sum('amount');

        if($fromDate->dayOfWeek == Carbon::SUNDAY && $toDate->dayOfWeek == Carbon::SATURDAY && $toDate < Carbon::now())
        {
            $profitLossQuery    = ProfitLoss::where('status', 1);
            $checkRecord        = clone $profitLossQuery;
            $rateQuery          = clone $profitLossQuery;

            $checkRecord        = $checkRecord->where('to_date', $toDate->copy()->format('Y-m-d'))->first();
            //if not processed yet
            if(!empty($checkRecord) && !empty($checkRecord->id)) {
                $shareConfirmButton = 2;
            }
            $lastRecord         = $profitLossQuery->orderBy('to_date', 'desc')->first();
            
            if(!empty($lastRecord) && !empty($lastRecord->id))
            {
                $restrictedDate = Carbon::createFromFormat('Y-m-d', $lastRecord->to_date);
                if(($restrictedDate->copy()->addDay()->format('d-m-Y') == $fromDate->copy()->format('d-m-Y')) && ($fromDate->diffInDays($toDate) == 6)) {
                    $shareConfirmButton = 1;
                }
            } elseif($fromDate->diffInDays($toDate) == 6) {
                $shareConfirmButton = 1;
            }

            //sales details for sale based share calculation
            $vehicleTypes   = VehicleType::where('status', 1)->orderBy('generic_quantity', 'desc')->get();
            //to get count of sales of product 1
            $productId      = [1];
            $rateQuery      = $rateQuery->where('share_type', 1)->where('from_date', $fromDate->copy()->format('Y-m-d'))->first();
            $saleBasedOwner = Owner::where('share_type', 1)->first();
            $ratePerFeet    = !empty($rateQuery) ? $rateQuery->share_rate : $saleBasedOwner->share_rate;
            $query = Sale::where('status', 1)->whereIn('product_id', $productId)->whereBetween('date_time', [$fromDate, $toDate]);

            $salesCount             = [];
            $totalSaleCount         = 0;
            $singleSalecount        = 0;
            $multipleSalecount      = 0;
            $saleProfitAmount       = [];
            $totalSaleProfitAmount  = 0;
            $percentProfitAmount    = 0;

            foreach ($vehicleTypes as $vehicleType) {
                $singleSaleCountQuery   = clone $query; //cloning query
                $multipleSaleCountQuery = clone $query; //cloning query

                $vehicleTypeId = $vehicleType->id;
                $singleSalecount = $singleSaleCountQuery->whereHas('vehicle', function ($qry) use($vehicleTypeId) {
                                    $qry->where('vehicle_type_id', $vehicleTypeId);
                                })->whereIn('measure_type', [1, 2])->count();

                $multipleSalecount = $multipleSaleCountQuery->whereHas('vehicle', function ($qry) use($vehicleTypeId) {
                                    $qry->where('vehicle_type_id', $vehicleTypeId);
                                })->where('measure_type', 3)->sum('quantity');

                $totalSaleCount = $totalSaleCount + $singleSalecount + $multipleSalecount;
                $totalSaleProfitAmount = $totalSaleProfitAmount + ($singleSalecount + $multipleSalecount) * $vehicleType->generic_quantity * $ratePerFeet;

                $salesCount[$vehicleType->id] = $singleSalecount + $multipleSalecount;
                $saleProfitAmount[$vehicleType->id] = ($singleSalecount + $multipleSalecount) * $vehicleType->generic_quantity * $ratePerFeet;
            }

            $balanceAmount = $totalCredit - ($totalDebit + $totalSaleProfitAmount);

            $percentProfitAmount = $balanceAmount/3;//($balanceAmount < 0) ? (($balanceAmount * -1)/3) : ($balanceAmount/3);
            
            $owners = Owner::where('status', 1)->whereHas('account', function($qry) {
                $qry->where('relation', 'owner');
            })->with('account')->get();

            foreach ($owners as $key => $owner) {
                if($owner->share_type == 1) {
                    $ownerShare[$owner->account_id] = $totalSaleProfitAmount;
                } else {
                    $ownerShare[$owner->account_id] = $percentProfitAmount;
                }
            }

            return view('account-statement.profit-loss-share', [
                    'fromDate'              => $fromDate,
                    'toDate'                => $toDate,
                    'totalDebit'            => $totalDebit,
                    'totalCredit'           => $totalCredit,
                    'restrictedDate'        => $restrictedDate,
                    'shareConfirmButton'    => $shareConfirmButton,
                    'vehicleTypes'          => $vehicleTypes,
                    'totalSaleCount'        => $totalSaleCount,
                    'salesCount'            => $salesCount,
                    'saleProfitAmount'      => $saleProfitAmount,
                    'totalSaleProfitAmount' => $totalSaleProfitAmount,
                    'ratePerFeet'           => $ratePerFeet,
                    'owners'                => $owners,
                    'ownerShare'            => $ownerShare,
                    'balanceAmount'         => $balanceAmount,
                ]);
        }
        
        return redirect(route('daily-statement-list-search'))->with("message", "Unable to show share options. Invalid date range! Date should Start from Sunday to Saturday.")->with("alert-class", "alert-danger");
    }

    /**
     * Return view for profit & loss generation
     */
    public function profitLossAction(Request $request)
    {
        $restrictedDate     = "";
        $transactionArray   = [];
        $saveFlagCount      = 0;

        $validator = Validator::make($request->all(), [
            "from_date" => "required|date_format:d-m-Y",
            "to_date"   => "required|date_format:d-m-Y|after:from_date",
        ]);

        if ($validator->fails()) 
        {
            return redirect()->back()->with("message", "Unable to process share options. Invalid date format!")->with("alert-class", "alert-danger");
        }

        $fromDate   = Carbon::createFromFormat('d-m-Y H:i:s', $request->get("from_date"). "00:00:00");
        $toDate     = Carbon::createFromFormat('d-m-Y H:i:s', $request->get("to_date"). "23:59:59");

        $transactionQuery = Transaction::where('status', 1)->whereBetween('date_time', [$fromDate, $toDate]);

        $totalCreditQuery = clone $transactionQuery;
        $totalCredit = $totalCreditQuery->where('credit_account_id', 2)->sum('amount');

        $totalDebitQuery = clone $transactionQuery;
        $totalDebit = $totalDebitQuery->whereIn('debit_account_id', [3, 4, 5, 6, 7, 8, 9])->sum('amount');

        if($fromDate->dayOfWeek == Carbon::SUNDAY && $toDate->dayOfWeek == Carbon::SATURDAY && $toDate < Carbon::now())
        {
            $lastRecord     = ProfitLoss::where('status', 1)->orderBy('to_date', 'desc')->first();

            if(!empty($lastRecord) && !empty($lastRecord->id))
            {
                $restrictedDate = Carbon::createFromFormat('Y-m-d', $lastRecord->to_date);
                if(($restrictedDate->copy()->addDay()->format('d-m-Y') != $fromDate->copy()->format('d-m-Y')) && ($fromDate->diffInDays($toDate) != 6)) {
                    return redirect()->back()
                        ->with("message", "Unable to process share options. Invalid date period! Allowed date from".($restrictedDate->copy()->addDay()->format('d-m-Y'))." to ".($restrictedDate->copy()->addDay(6)->format('d-m-Y')))
                        ->with("alert-class", "alert-danger");
                }
            } elseif($fromDate->diffInDays($toDate) != 6) {
                return redirect()->back()
                    ->with("message", "Unable to process share options. Invalid date period! Allowed date from".($restrictedDate->copy()->addDay()->format('d-m-Y'))." to ".($restrictedDate->copy()->addDay(6)->format('d-m-Y')))
                        ->with("alert-class", "alert-danger");
            }

            //sales details for sale based share calculation
            $vehicleTypes   = VehicleType::where('status', 1)->orderBy('generic_quantity', 'desc')->get();
            //to get count of sales of product 1
            $productId      = [1];
            $saleBasedOwner = Owner::where('share_type', 1)->first();
            $ratePerFeet    = !empty($saleBasedOwner) ? $saleBasedOwner->share_rate : 1;
            $query = Sale::where('status', 1)->whereIn('product_id', $productId)->whereBetween('date_time', [$fromDate, $toDate]);

            $singleSalecount        = 0;
            $multipleSalecount      = 0;
            $totalSaleProfitAmount  = 0;
            $percentProfitAmount    = 0;

            foreach ($vehicleTypes as $vehicleType) {
                $singleSaleCountQuery   = clone $query; //cloning query
                $multipleSaleCountQuery = clone $query; //cloning query

                $vehicleTypeId = $vehicleType->id;
                $singleSalecount = $singleSaleCountQuery->whereHas('vehicle', function ($qry) use($vehicleTypeId) {
                                    $qry->where('vehicle_type_id', $vehicleTypeId);
                                })->whereIn('measure_type', [1, 2])->count();

                $multipleSalecount = $multipleSaleCountQuery->whereHas('vehicle', function ($qry) use($vehicleTypeId) {
                                    $qry->where('vehicle_type_id', $vehicleTypeId);
                                })->where('measure_type', 3)->sum('quantity');

                $totalSaleProfitAmount = $totalSaleProfitAmount + ($singleSalecount + $multipleSalecount) * $vehicleType->generic_quantity * $ratePerFeet;
            }

            $balanceAmount = $totalCredit - ($totalDebit + $totalSaleProfitAmount);

            $percentProfitAmount = $balanceAmount/3;
            
            $profitAndLossAccount = Account::where('account_name','Profit And Loss Share')->first();
            if($profitAndLossAccount) {
                $profitAndLossAccountId = $profitAndLossAccount->id;
            } else {
                return redirect()->back()->withInput()->with("message","Failed to generate share.Try again after reloading the page!<small class='pull-right'>Cash account not found!.  #00/00</small>")->with("alert-class","alert-danger");
            }

            $owners = Owner::where('status', 1)->whereHas('account', function($qry) {
                $qry->where('relation', 'owner');
            })->with('account')->get();

            foreach ($owners as $key => $owner) {
                $transaction = new Transaction;
                if($owner->share_type == 1) {
                    //$ownerShare[$owner->account_id] = $totalSaleProfitAmount;
                    $transaction->debit_account_id  = $profitAndLossAccountId;
                    $transaction->credit_account_id = $owner->account_id;
                    $transaction->amount            = $totalSaleProfitAmount;
                    $transaction->date_time         = Carbon::createFromFormat('d-m-Y H:i:s', $request->get("to_date"). "23:55:00");//$toDate;
                    $transaction->particulars       = ("Profit share based on sale, credited for the time period : ".
                                                            $fromDate->copy()->format('d-m-Y'). " - ". $toDate->copy()->format('d-m-Y'). 
                                                            ". [Rate : ". $ratePerFeet. "]");
                    $transaction->status            = 1;
                    $transaction->created_user_id   = Auth::user()->id;
                } else {
                    //$ownerShare[$owner->account_id] = $percentProfitAmount;
                    if($percentProfitAmount < 0) {
                        $transaction->debit_account_id  = $owner->account_id;
                        $transaction->credit_account_id = $profitAndLossAccountId;
                        $transaction->amount            = ($percentProfitAmount * (-1));
                    } else {
                        $transaction->debit_account_id  = $profitAndLossAccountId;
                        $transaction->credit_account_id = $owner->account_id;
                        $transaction->amount            = $percentProfitAmount;
                    }
                    $transaction->date_time             = Carbon::createFromFormat('d-m-Y H:i:s', $request->get("to_date"). "23:55:00");//$toDate;
                    $transaction->particulars           = ("Profit share , credited for the time period : ".
                                                                $fromDate->copy()->format('d-m-Y'). " - ". $toDate->copy()->format('d-m-Y').
                                                                ". [Percentage : 33.33]");
                    $transaction->status                = 1;
                    $transaction->created_user_id       = Auth::user()->id;
                }
                if($transaction->save()) {
                    $profitLoss = new ProfitLoss;
                    $profitLoss->transaction_id = $transaction->id;
                    $profitLoss->from_date      = $fromDate->copy()->format('Y-m-d');
                    $profitLoss->to_date        = $toDate->copy()->format('Y-m-d');
                    $profitLoss->owner_id       = $owner->id;
                    $profitLoss->share_type     = $owner->share_type;
                    $profitLoss->share_type     = $owner->share_type == 1 ? $ratePerFeet : round((100/3), 2);
                    $profitLoss->amount         = $transaction->amount;
                    $profitLoss->status         = 1;
                    if($profitLoss->save()) {
                        $saveFlagCount = $saveFlagCount + 1;
                    } else {
                        return redirect()->back()->withInput()->with("message","Failed to generate share.Try again after reloading the page!")->with("alert-class","alert-danger");
                    }
                } else {
                    return redirect()->back()->withInput()->with("message","Failed to generate share.Try again after reloading the page!")->with("alert-class","alert-danger");
                }

            }

            if($saveFlagCount == count($owners)) {
                return redirect()->back()->with("message","Successfully allotted profit-loss values!")->with("alert-class","alert-success");
            }
        }
        return redirect()->back()->with("message", "Unable to process share options. Invalid date range! Date should Start from Sunday to Saturday.")->with("alert-class", "alert-danger");
    }

    /**
     * Return view for account editing
     */
    public function edit(Request $request)
    {
        $accountId = !empty($request->get('account_id')) ? $request->get('account_id') : 0;

        if(!empty($accountId) && $accountId != 0) {
            $account = Account::where('id', $accountId)->where('type', 'personal')->whereIn('relation', ['supplier','customer','contractor','general', 'operator'])->with('accountDetail')->first();

            if(empty($account) || empty($account->id)) {
                return redirect(route('account-list'))->with("message","Something went wrong! Selected record not found. Try again after reloading the page!<small class='pull-right'> #07/03</small>")->with("alert-class","alert-danger");
            }
        } else {
            return redirect(route('account-list'))->with("message","Something went wrong! Selected record not found. Try again after reloading the page!<small class='pull-right'> #07/04</small>")->with("alert-class","alert-danger");
        }

        return view('account.edit',[
                'account' => $account
            ]);
    }

    /**
     * Handle account updation
     */
    public function updationAction(AccountUpdationRequest $request)
    {
        $accountId          = !empty($request->get('account_id')) ? $request->get('account_id') : 0;
        $description        = $request->get('description');
        $name               = $request->get('name');
        $phone              = $request->get('phone');
        $address            = $request->get('address');
        $relation           = $request->get('relation_type');

        $account = Account::find($accountId);
        if(!empty($account) && $account->type == 'personal') {
            $account->description       = $description;
            $account->relation          = $relation;
            if($account->save()) {
                $accountDetails = AccountDetail::where('account_id', $account->id)->first();
                $accountDetails->name       = $name;
                $accountDetails->phone      = $phone;
                $accountDetails->address    = $address;
                if($accountDetails->save()) {
                    return redirect(route('account-list'))->with("message","Successfully updated.")->with("alert-class","alert-success");
                } else{
                    return redirect(route('account-list'))->with("message","Failed to update the account details. Try again after reloading the page!<small class='pull-right'> #07/05</small>")->with("alert-class","alert-danger");
                }
            } else {
                return redirect(route('account-list'))->with("message","Failed to update the account details. Try again after reloading the page!<small class='pull-right'> #07/06</small>")->with("alert-class","alert-danger");
            }
        } else {
            return redirect(route('account-list'))->with("message","Failed to update the account details. Try again after reloading the page!<small class='pull-right'> #07/07</small>")->with("alert-class","alert-danger");
        }
    }
}
