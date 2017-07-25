<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DateTime;
use App\Models\Account;
use App\Models\EmployeeAttendance;
use App\Models\Employee;
use App\Models\Transaction;
use App\Models\Excavator;
use App\Models\ExcavatorReading;
use App\Models\Jackhammer;
use App\Models\JackhammerReading;
use App\Http\Requests\EmployeeAttendanceRegistrationRequest;
use App\Http\Requests\ExcavatorReadingRegistrationRequest;
use App\Http\Requests\JackhammerReadingRegistrationRequest;
use App\Models\Sale;

class DailyStatementController extends Controller
{
    /**
     * Return view for daily statement registration
     */
    public function register()
    {
        $presentEmployeeAccounts    = [];
        $presentEmployees           = [];
        $presentexcavatorReadings   = [];
        $presentjackhammerReadings  = [];
        $today = Carbon::now('Asia/Kolkata');
        
        $employeeAttendance = EmployeeAttendance::where('date',$today->format('Y-m-d'))->get();
        /*foreach ($employeeAttendance as $attendance) {
            $presentEmployees[]         = $attendance->employee_id;
            $presentEmployeeAccounts[]  = $attendance->employee->account->id;
        }*/
        $excavatorReadings = ExcavatorReading::where('date',$today->format('Y-m-d'))->get();
        /*foreach ($excavatorReadings as $excavatorReading) {
            $presentexcavatorReadings[] = $excavatorReading->excavator_id;
        }*/
        $jackhammerReadings = JackhammerReading::where('date',$today->format('Y-m-d'))->get();
        /*foreach ($jackhammerReadings as $jackhammerReading) {
            $presentjackhammerReadings[] = $jackhammerReading->jackhammer_id;
        }*/

        $employeeAccounts   = Account::where('relation','employee')->whereNotIn('id', $presentEmployeeAccounts)->get();
        $employees          = Employee::whereNotIn('id', $presentEmployees)->get();
        $excavators         = Excavator::whereNotIn('id', $presentexcavatorReadings)->get();
        $jackhammers        = Jackhammer::whereNotIn('id', $presentjackhammerReadings)->get();

        if(!empty($employeeAccounts) && !empty($employeeAttendance) && !empty($employees) && !empty($excavators) && !empty($excavatorReadings) && !empty($jackhammers)) {
            return view('daily-statement.register',[
                    'today' => $today,
                    'employeeAccounts'   => $employeeAccounts,
                    'employeeAttendance' => $employeeAttendance,
                    'employees'          => $employees,
                    'excavators'         => $excavators,
                    'excavatorReadings'  => $excavatorReadings,
                    'jackhammers'        => $jackhammers,
                    'jackhammerReadings' => $jackhammerReadings,
                ]);
        } else {
            return view('daily-statement.register',[
                    'today' => $today
                ]);
        }
    }

    /**
     * Handle attendance of the employee
     */
    public function employeeAttendanceAction(EmployeeAttendanceRegistrationRequest $request)
    {
        $date               = $request->get('attendance_date');
        $employeeId         = $request->get('attendance_employee_id');
        $employeeAccountId  = $request->get('attendance_account_id');
        $wage               = $request->get('attendance_wage');

        //converting date and time to sql datetime format
        $dateTime = date('Y-m-d H:i:s', strtotime($date.' '.'00:00:00'));
        $date = date('Y-m-d', strtotime($date.' '.'00:00:00'));

        $employee = Employee::where('account_id', $employeeAccountId)->first();
        if(!empty($employee) && $employeeId == $employee->id) {
            if($employee->employee_type == "staff") {
                return redirect()->back()->with("message","Selected employee has monthly salary scheme; Use monthly statement for this user.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'employee');    
            }
        } else {
            return redirect()->back()->with("message","Something went wrong! Employee not found.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'employee');
        }

        $recordFlag = EmployeeAttendance::where('date',$date)->where('employee_id', $employeeId)->get();
        if(count($recordFlag) > 0) {
            return redirect()->back()->with("message","Error! Attendance of this user has already marked for ".$date)->with("alert-class","alert-danger")->with('controller_tab_flag', 'employee');
        }

        $labouAttendanceAccount = Account::where('account_name','Labour Attendance')->first();
        if($labouAttendanceAccount) {
            $labouAttendanceAccountId = $labouAttendanceAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Labour attendance account not found.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'employee');
        }

        $transaction = new Transaction;
        $transaction->debit_account_id  = $labouAttendanceAccountId; //labour wage account id
        $transaction->credit_account_id = $employeeAccountId;
        $transaction->amount            = !empty($wage) ? $wage : '0';
        $transaction->date_time         = $dateTime;
        $transaction->particulars       = "Labour wage generated";
        $transaction->status            = 1;
        $transaction->created_user_id   = Auth::user()->id;

        if($transaction->save()) {
            $employeeAttendance = new EmployeeAttendance;
            $employeeAttendance->date           = $date;
            $employeeAttendance->employee_id    = $employeeId;
            $employeeAttendance->wage           = $wage;
            $employeeAttendance->transaction_id = $transaction->id;
            $employeeAttendance->status         = 1;
            
            if($employeeAttendance->save()) {
                return redirect()->back()->with("message","Successfully saved.")->with("alert-class","alert-success")->with('controller_tab_flag', 'employee');
            } else {
                return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the attendance details. Try after reloading the page.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'employee');
            }
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the attendance details. Try after reloading the page.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'employee');
        }
    }

    public function excavatorReadingsAction(ExcavatorReadingRegistrationRequest $request)
    {
        $rentTypeFlag   = 0;
        $date           = $request->get('excavator_date');
        $excavatorId    = $request->get('excavator_id');
        $bucketHour     = $request->get('excavator_bucket_hour');
        $breakerHour    = $request->get('excavator_breaker_hour');
        $operatorName   = $request->get('excavator_operator');
        $operatorBata   = $request->get('excavator_operator_bata');

        //converting date and time to sql datetime format
        $dateTime = date('Y-m-d H:i:s', strtotime($date.' '.'00:00:00'));
        $date = date('Y-m-d', strtotime($date.' '.'00:00:00'));

        $excavator = Excavator::where('id', $excavatorId)->first();
        if(!empty($excavator)) {
            $excavatorContractorAccountId = $excavator->contractor_account_id;
            $rentType       = $excavator->rent_type;
            $bucketRate     = $excavator->rent_hourly_bucket;
            $breakerRate    = $excavator->rent_hourly_breaker;
            $bucketHour     = (!empty($bucketHour)) ? $bucketHour : 0;
            $breakerHour    = (!empty($breakerHour)) ? $breakerHour : 0;
            if($rentType == 'hourly'){
                $bill = ($bucketHour * $bucketRate) + ($breakerHour * $breakerRate) + $operatorBata;
            } else {
                $rentTypeFlag = 1;
            }
        } else {
            return redirect()->back()->with("message","Something went wrong! Excavator not found.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'excavator');
        }

        $recordFlag = ExcavatorReading::where('date',$date)->where('excavator_id', $excavatorId)->get();
        if(count($recordFlag) > 0) {
            return redirect()->back()->with("message","Error! Reading of this excavator has already marked for ".date('d-m-Y', strtotime($date)))->with("alert-class","alert-danger")->with('controller_tab_flag', 'excavator');
        }

        $excavatorReadingAccount = Account::where('account_name','Excavator Reading')->first();
        if($excavatorReadingAccount) {
            $excavatorReadingAccountId = $excavatorReadingAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Excavator reading account not found.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'excavator');
        }

        $temp = ("Excavator Rent : Bucket : ".$bucketHour." * ".$bucketRate." = ".($bucketHour*$bucketRate)." / Breaker : ".$breakerHour." * ".$breakerRate." = ".($breakerHour * $breakerRate)." / Bata : ".$operatorBata);

        if($rentTypeFlag == 0) {
            $transaction = new Transaction;
            $transaction->debit_account_id  = $excavatorReadingAccountId; //excavator reading account id
            $transaction->credit_account_id = $excavatorContractorAccountId;
            $transaction->amount            = $bill;
            $transaction->date_time         = $dateTime;
            $transaction->particulars       = $temp;
            $transaction->status            = 1;
            $transaction->created_user_id   = Auth::user()->id;

            if($transaction->save()) {
                $excavatorReading = new ExcavatorReading;
                $excavatorReading->date             = $date;
                $excavatorReading->excavator_id     = $excavatorId;
                $excavatorReading->transaction_id   = $transaction->id;
                $excavatorReading->bucket_hour      = $bucketHour;
                $excavatorReading->breaker_hour     = $breakerHour;
                $excavatorReading->operator_name    = $operatorName;
                $excavatorReading->bata             = $operatorBata;
                $excavatorReading->status           = 1;
            } else {
                return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the reading details. Try after reloading the page.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'excavator');
            }
        } else {
            $excavatorReading = new ExcavatorReading;
            $excavatorReading->date             = $date;
            $excavatorReading->excavator_id     = $excavatorId;
            $excavatorReading->bucket_hour      = $bucketHour;
            $excavatorReading->breaker_hour     = $breakerHour;
            $excavatorReading->operator_name    = $operatorName;
            $excavatorReading->bata             = $operatorBata;
            $excavatorReading->status           = 1;
        }
            
        if($excavatorReading->save()) {
            return redirect()->back()->with("message","Successfully saved.")->with("alert-class","alert-success")->with('controller_tab_flag', 'excavator');
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the reading details. Try after reloading the page.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'excavator');
        }
    }

    public function jackhammerReadingsAction(JackhammerReadingRegistrationRequest $request)
    {
        $rentTypeFlag   = 0;
        $date           = $request->get('jackhammer_date');
        $jackhammerId   = $request->get('jackhammer_id');
        $depthPerPit    = $request->get('jackhammer_depth_per_pit');
        $noOfPit        = $request->get('jackhammer_no_of_pit');
        $totalPitDepth  = $request->get('jackhammer_total_pit_depth');

        //converting date and time to sql datetime format
        $dateTime = date('Y-m-d H:i:s', strtotime($date.' '.'00:00:00'));
        $date = date('Y-m-d', strtotime($date.' '.'00:00:00'));

        $jackhammer = Jackhammer::where('id', $jackhammerId)->first();
        if(!empty($jackhammer)) {
            $jackhammerContractorAccountId = $jackhammer->contractor_account_id;
            $rentType       = $jackhammer->rent_type;
            $rentPerFeet    = $jackhammer->rent_feet;

            if($rentType == 'per_feet'){
                $bill = ($totalPitDepth * $rentPerFeet);
            } else {
                //$rentTypeFlag   = 1;
                return redirect()->back()->with("message","Error! Selected jackhammer has a diffrent rent scheme.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'employee');    
            }
        } else {
            return redirect()->back()->with("message","Something went wrong! Jackhammer not found.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'jackhammer');
        }

        $recordFlag = JackhammerReading::where('date',$date)->where('jackhammer_id', $jackhammerId)->get();
        if(count($recordFlag) > 0) {
            return redirect()->back()->with("message","Error! Reading of this jackhammer has already marked for ".date('d-m-Y', strtotime($date)))->with("alert-class","alert-danger")->with('controller_tab_flag', 'jackhammer');
        }

        $jackhammerReadingAccount = Account::where('account_name','Jackhammer Reading')->first();
        if($jackhammerReadingAccount) {
            $jackhammerReadingAccountId = $jackhammerReadingAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Jackhammer reading account not found.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'jackhammer');
        }

        $temp = ("Jackhammer rent : ".$depthPerPit." * ".$noOfPit." * ".$rentPerFeet." = ".($depthPerPit*$noOfPit*$rentPerFeet));

        //if($rentTypeFlag == 0){
        $transaction = new Transaction;
        $transaction->debit_account_id  = $jackhammerReadingAccountId; //jackhammer reading account id
        $transaction->credit_account_id = $jackhammerContractorAccountId;
        $transaction->amount            = $bill;
        $transaction->date_time         = $dateTime;
        $transaction->particulars       = $temp;
        $transaction->status            = 1;
        $transaction->created_user_id   = Auth::user()->id;

        if($transaction->save()) {
            $jackhammerReading = new JackhammerReading;
            $jackhammerReading->date             = $date;
            $jackhammerReading->jackhammer_id    = $jackhammerId;
            $jackhammerReading->transaction_id   = $transaction->id;
            $jackhammerReading->total_pit_depth  = $totalPitDepth;
            $jackhammerReading->status           = 1;

            if($jackhammerReading->save()) {
                return redirect()->back()->with("message","Successfully saved.")->with("alert-class","alert-success")->with('controller_tab_flag', 'jackhammer');
            } else {
                return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the reading details. Try after reloading the page.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'jackhammer');
            }
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the reading details. Try after reloading the page.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'jackhammer');
        }
        /*} else {
            $jackhammerReading = new JackhammerReading;
            $jackhammerReading->date             = $date;
            $jackhammerReading->jackhammer_id    = $jackhammerId;
            $jackhammerReading->total_pit_depth  = $totalPitDepth;
            $jackhammerReading->status           = 1;
        }*/
    }

    /**
     * Return view for daily statement listing / employee attendance
     */
    public function employeeAttendanceList(Request $request)
    {
        $accountId  = !empty($request->get('attendance_account_id')) ? $request->get('attendance_account_id') : 0;
        $employeeId = !empty($request->get('attendance_employee_id')) ? $request->get('attendance_employee_id') : 0;
        $fromDate   = !empty($request->get('attendance_from_date')) ? $request->get('attendance_from_date') : '';
        $toDate     = !empty($request->get('attendance_to_date')) ? $request->get('attendance_to_date') : '';

        $accounts   = Account::where('relation', 'employee')->where('status', '1')->get();
        $employees  = Employee::with(['account.accountDetail'])->where('status', '1')->get();

        $query = EmployeeAttendance::where('status', 1);

        if(!empty($accountId) && $accountId != 0) {
            $selectedAccount = Account::find($accountId);
            if(!empty($selectedAccount) && !empty($selectedAccount->id)) {
                $selectedAccountName = $selectedAccount->account_name;

                $query = $query->whereHas('transaction', function ($q) use($accountId) {
                    $q->whereHas('creditAccount', function ($qry) use($accountId) {
                        $qry->where('id', $accountId);
                    });
                });
            } else {
                $accountId = 0;
            }
        } else {
            $selectedAccountName = '';
        }

        if(!empty($employeeId) && $employeeId != 0) {
            $selectedEmployee = Employee::find($employeeId);
            if(!empty($selectedEmployee) && !empty($selectedEmployee->id)) {
                $selectedEmployeeName = $selectedEmployee->name;

                $query = $query->where('employee_id', $employeeId);
            } else {
                $employeeId = 0;
            }
        } else {
            $selectedProductName = '';
        }

        if(!empty($fromDate)) {
            $searchFromDate = new DateTime($fromDate);
            $searchFromDate = $searchFromDate->format('Y-m-d');
            $query = $query->where('date', '>=', $searchFromDate);
        }

        if(!empty($toDate)) {
            $searchToDate = new DateTime($toDate);
            $searchToDate = $searchToDate->format('Y-m-d');
            $query = $query->where('date', '<=', $searchToDate);
        }

        $employeeAttendance = $query->with(['employee.account.accountDetail'])->orderBy('date','desc')->paginate(10);
        
        return view('daily-statement.list',[
                'accounts'              => $accounts,
                'employees'             => $employees,
                'employeeAttendance'    => $employeeAttendance,
                'accountId'             => $accountId,
                'employeeId'            => $employeeId,
                'fromDate'              => $fromDate,
                'toDate'                => $toDate,
                'excavatorReadings'     => [],
                'jackhammerReadings'    => [],
            ]);
    }

    /**
     * Return view for daily statement listing / excavator reading
     */
    public function excavatorReadingList(Request $request)
    {
        $accountId      = !empty($request->get('excavator_account_id')) ? $request->get('excavator_account_id') : 0;
        $excavatorId    = !empty($request->get('excavator_id')) ? $request->get('excavator_id') : 0;
        $fromDate       = !empty($request->get('excavator_from_date')) ? $request->get('excavator_from_date') : '';
        $toDate         = !empty($request->get('excavator_to_date')) ? $request->get('excavator_to_date') : '';

        $accounts   = Account::where('type', 'personal')->where('status', '1')->get();
        $excavators = Excavator::with(['account.accountDetail'])->where('status', '1')->get();

        $query = ExcavatorReading::where('status', 1);

        if(!empty($accountId) && $accountId != 0) {
            $selectedAccount = Account::find($accountId);
            if(!empty($selectedAccount) && !empty($selectedAccount->id)) {
                $selectedAccountName = $selectedAccount->account_name;

                $query = $query->whereHas('transaction', function ($q) use($accountId) {
                    $q->whereHas('creditAccount', function ($qry) use($accountId) {
                        $qry->where('id', $accountId);
                    });
                });
            } else {
                $accountId = 0;
            }
        } else {
            $selectedAccountName = '';
        }

        if(!empty($excavatorId) && $excavatorId != 0) {
            $selectedExcavator = Excavator::find($excavatorId);
            if(!empty($selectedExcavator) && !empty($selectedExcavator->id)) {
                $selectedExcavatorName = $selectedExcavator->name;

                $query = $query->where('excavator_id', $excavatorId);
            } else {
                $excavatorId = 0;
            }
        } else {
            $selectedExcavatorName = '';
        }

        if(!empty($fromDate)) {
            $searchFromDate = new DateTime($fromDate);
            $searchFromDate = $searchFromDate->format('Y-m-d');
            $query = $query->where('date', '>=', $searchFromDate);
        }

        if(!empty($toDate)) {
            $searchToDate = new DateTime($toDate);
            $searchToDate = $searchToDate->format('Y-m-d');
            $query = $query->where('date', '<=', $searchToDate);
        }

        $excavatorReadings = $query->with(['excavator.account.accountDetail'])->orderBy('date','desc')->paginate(10);
        
        return view('daily-statement.list',[
                'accounts'              => $accounts,
                'excavators'            => $excavators,
                'excavatorReadings'     => $excavatorReadings,
                'accountId'             => $accountId,
                'excavatorId'           => $excavatorId,
                'fromDate'              => $fromDate,
                'toDate'                => $toDate,
                'employeeAttendance'    => [],
                'jackhammerReadings'    => [],
            ]);
    }

    /**
     * Return view for daily statement listing / daily statement
     */
    public function jackhammerReadingList(Request $request)
    {
        /*$jackhammerReadings = JackhammerReading::paginate(10);

        if(empty($jackhammerReadings)) {
            $jackhammerReadings = [];
        }
        return view('daily-statement.list',[
                'employeeAttendance'    => [],
                'excavatorReadings'     => [],
                'jackhammerReadings'    => $jackhammerReadings
            ]);*/

        $accountId      = !empty($request->get('jackhammer_account_id')) ? $request->get('jackhammer_account_id') : 0;
        $jackhammerId   = !empty($request->get('jackhammer_id')) ? $request->get('jackhammer_id') : 0;
        $fromDate       = !empty($request->get('jackhammer_from_date')) ? $request->get('jackhammer_from_date') : '';
        $toDate         = !empty($request->get('jackhammer_to_date')) ? $request->get('jackhammer_to_date') : '';

        $accounts    = Account::where('type', 'personal')->where('status', '1')->get();
        $jackhammers = Jackhammer::with(['account.accountDetail'])->where('status', '1')->get();

        $query = JackhammerReading::where('status', 1);

        if(!empty($accountId) && $accountId != 0) {
            $selectedAccount = Account::find($accountId);
            if(!empty($selectedAccount) && !empty($selectedAccount->id)) {
                $selectedAccountName = $selectedAccount->account_name;

                $query = $query->whereHas('transaction', function ($q) use($accountId) {
                    $q->whereHas('creditAccount', function ($qry) use($accountId) {
                        $qry->where('id', $accountId);
                    });
                });
            } else {
                $accountId = 0;
            }
        } else {
            $selectedAccountName = '';
        }

        if(!empty($jackhammerId) && $jackhammerId != 0) {
            $selectedJackhammer = Jackhammer::find($jackhammerId);
            if(!empty($selectedJackhammer) && !empty($selectedJackhammer->id)) {
                $selectedExcavatorName = $selectedJackhammer->name;

                $query = $query->where('jackhammer_id', $jackhammerId);
            } else {
                $jackhammerId = 0;
            }
        } else {
            $selectedExcavatorName = '';
        }

        if(!empty($fromDate)) {
            $searchFromDate = new DateTime($fromDate);
            $searchFromDate = $searchFromDate->format('Y-m-d');
            $query = $query->where('date', '>=', $searchFromDate);
        }

        if(!empty($toDate)) {
            $searchToDate = new DateTime($toDate);
            $searchToDate = $searchToDate->format('Y-m-d');
            $query = $query->where('date', '<=', $searchToDate);
        }

        $jackhammerReadings = $query->with(['jackhammer.account.accountDetail'])->orderBy('date','desc')->paginate(10);
        
        return view('daily-statement.list',[
                'accounts'              => $accounts,
                'jackhammers'           => $jackhammers,
                'jackhammerReadings'    => $jackhammerReadings,
                'accountId'             => $accountId,
                'jackhammerId'          => $jackhammerId,
                'fromDate'              => $fromDate,
                'toDate'                => $toDate,
                'employeeAttendance'    => [],
                'excavatorReadings'     => [],
            ]);
    }

    /**
     * Return view for daily statement
     */
    public function dailySatementSearch(Request $request)
    {
        $sales = 0;
        $purchases = 0;
        $labourWage = 0;
        $excavatorReadingRent = 0;
        $jackhammerRent = 0;
        $employeeSalary = 0;
        $excavatorMonthlyRent = 0;
        $royalty = 0;
        $fromDate = 0;
        $toDate = 0;
        $totalDebit = 0;
        $totalCredit = 0;

        $fromDate   = !empty($request->get('from_date')) ? $request->get('from_date') : '';
        $toDate     = !empty($request->get('to_date')) ? $request->get('to_date') : '';

        $query = Transaction::where('status', 1);

        if(empty($fromDate) && empty($toDate)) {
            $query = $query->whereDate('date_time', Carbon::today()->toDateString());
        } else if(!empty($fromDate) && empty($toDate)){
            $searchFromDate = Carbon::createFromFormat('d-m-Y', $fromDate);

            $query = $query->whereDate('date_time', $searchFromDate->toDateString());
        } else if(empty($fromDate) && !empty($toDate)) {
            $searchToDate = Carbon::createFromFormat('d-m-Y', $toDate);

            $query = $query->whereDate('date_time', $searchToDate->toDateString());
        } else {
            $searchFromDate = Carbon::createFromFormat('d-m-Y H:i:s', $fromDate." 00:00:00");
            $searchToDate = Carbon::createFromFormat('d-m-Y H:i:s', $toDate." 23:59:59");

            $query = $query->whereBetween('date_time', [$searchFromDate, $searchToDate]);
        }

        $transactions = $query->get();
        
        foreach ($transactions as $key => $transaction) {
            if($transaction->credit_account_id == 2) {
                $sales = $sales + $transaction->amount;
            }
            switch ($transaction->debit_account_id) {
                case '3':
                    $purchases = $purchases + $transaction->amount;
                    break;
                case '4':
                    $labourWage = $labourWage + $transaction->amount;
                    break;
                case '5':
                    $excavatorReadingRent = $excavatorReadingRent + $transaction->amount;
                    break;
                case '6':
                    $jackhammerRent = $jackhammerRent + $transaction->amount;
                    break;
                case '7':
                    $employeeSalary = $employeeSalary + $transaction->amount;
                    break;
                case '8':
                    $excavatorMonthlyRent = $excavatorMonthlyRent + $transaction->amount;
                    break;
                case '9':
                    $royalty = $royalty + $transaction->amount;
                    break;
                
                default:
                    break;
            }
        }

        $totalCredit    = $sales;
        $totalDebit     = $purchases + $labourWage + $excavatorReadingRent + $jackhammerRent + $employeeSalary + $excavatorMonthlyRent + $royalty;

        return view('daily-statement.statement',[
                'sales'                 => $sales,
                'purchases'             => $purchases,
                'labourWage'            => $labourWage,
                'excavatorReadingRent'  => $excavatorReadingRent,
                'jackhammerRent'        => $jackhammerRent,
                'employeeSalary'        => $employeeSalary,
                'excavatorMonthlyRent'  => $excavatorMonthlyRent,
                'royalty'               => $royalty,
                'fromDate'              => $fromDate,
                'toDate'                => $toDate,
                'totalDebit'            => $totalDebit,
                'totalCredit'           => $totalCredit
            ]);
    }
}