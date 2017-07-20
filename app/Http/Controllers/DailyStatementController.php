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
    public function employeeAttendanceList()
    {
        $employeeAttendance = EmployeeAttendance::paginate(10);

        if(empty($employeeAttendance)) {
            $employeeAttendance = [];
        }
        return view('daily-statement.list',[
                    'employeeAttendance'    => $employeeAttendance,
                    'excavatorReadings'     => [],
                    'jackhammerReadings'    => [],
                ]);
    }

    /**
     * Return view for daily statement listing / excavator reading
     */
    public function excavatorReadingList()
    {
        $excavatorReadings  = ExcavatorReading::paginate(10);

        if(empty($excavatorReadings)) {
            $excavatorReadings = [];
        }
        return view('daily-statement.list',[
                'employeeAttendance'    => [],
                'excavatorReadings'     => $excavatorReadings,
                'jackhammerReadings'    => []
            ]);
    }

    /**
     * Return view for daily statement listing / daily statement
     */
    public function jackhammerReadingList()
    {
        $jackhammerReadings = JackhammerReading::paginate(10);

        if(empty($jackhammerReadings)) {
            $jackhammerReadings = [];
        }
        return view('daily-statement.list',[
                'employeeAttendance'    => [],
                'excavatorReadings'     => [],
                'jackhammerReadings'    => $jackhammerReadings
            ]);
    }

    /**
     * Return view for daily statement
     */
    public function dailySatementSearch(Request $request)
    {
        $fromDate   = !empty($request->get('from_date')) ? $request->get('from_date') : '';
        $toDate     = !empty($request->get('to_date')) ? $request->get('to_date') : '';

        //$totalDebit     = Transaction::where('debit_account_id', $accountId)->sum('amount');
        //$totalCredit    = Transaction::where('credit_account_id', $accountId)->sum('amount');

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

        $transactions = $query->orderBy('date_time','desc')->paginate(10);
        //dd($transactions);
        return view('daily-statement.statement',[
                'transactions'          => $transactions,
                'fromDate'              => $fromDate,
                'toDate'                => $toDate,
                //'totalDebit'            => $totalDebit,
                //'totalCredit'           => $totalCredit,
            ]);
    }
}