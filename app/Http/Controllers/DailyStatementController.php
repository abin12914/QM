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
use App\Http\Requests\DeleteAttendanceRequest;
use App\Http\Requests\DeleteExcavatorReadingRequest;
use App\Http\Requests\DeleteJackhammerReadingRequest;
use App\Models\Sale;
use App\Models\ProfitLoss;

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
        
        $employeeAttendance = EmployeeAttendance::where('date',$today->format('Y-m-d'))->with(['employee.account.accountDetail'])->get();
        /*foreach ($employeeAttendance as $attendance) {
            $presentEmployees[]         = $attendance->employee_id;
            $presentEmployeeAccounts[]  = $attendance->employee->account->id;
        }*/
        $excavatorReadings = ExcavatorReading::where('date',$today->format('Y-m-d'))->with(['excavator.account'])->get();
        /*foreach ($excavatorReadings as $excavatorReading) {
            $presentexcavatorReadings[] = $excavatorReading->excavator_id;
        }*/
        $jackhammerReadings = JackhammerReading::where('date',$today->format('Y-m-d'))->with(['jackhammer.account'])->get();
        /*foreach ($jackhammerReadings as $jackhammerReading) {
            $presentjackhammerReadings[] = $jackhammerReading->jackhammer_id;
        }*/

        $employeeAccounts   = Account::where('status', 1)->where('relation','employee')->whereNotIn('id', $presentEmployeeAccounts)->get();
        $operatorAccounts   = Account::where('status', 1)->where('relation','operator')->get();
        $employees          = Employee::where('status', 1)->whereNotIn('id', $presentEmployees)->with(['account.accountDetail'])->get();
        $excavators         = Excavator::where('status', 1)->whereNotIn('id', $presentexcavatorReadings)->with(['account'])->get();
        $jackhammers        = Jackhammer::where('status', 1)->whereNotIn('id', $presentjackhammerReadings)->get();

        return view('daily-statement.register',[
                'today' => $today,
                'employeeAccounts'   => $employeeAccounts,
                'operatorAccounts'   => $operatorAccounts,
                'employeeAttendance' => $employeeAttendance,
                'employees'          => $employees,
                'excavators'         => $excavators,
                'excavatorReadings'  => $excavatorReadings,
                'jackhammers'        => $jackhammers,
                'jackhammerReadings' => $jackhammerReadings,
            ]);
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
                return redirect()->back()->with("message","Failed to save the attendance details.<br>Selected employee has monthly salary scheme; Use monthly statement for this employee!<small class='pull-right'> #04/01</small>")->with("alert-class","alert-danger")->with('controller_tab_flag', 'employee');
            }
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the attendance details. Try again after reloading the page!<small class='pull-right'> #04/02</small>")->with("alert-class","alert-danger")->with('controller_tab_flag', 'employee');
        }

        $recordFlag = EmployeeAttendance::where('date',$date)->where('employee_id', $employeeId)->get();
        if(count($recordFlag) > 0) {
            return redirect()->back()->with("message","Failed to save the attendance details.<br>Attendance of this user has already marked for ".$date. "!<small class='pull-right'> #04/03</small>")->with("alert-class","alert-danger")->with('controller_tab_flag', 'employee');
        }

        $labouAttendanceAccount = Account::where('account_name','Labour Attendance')->first();
        if($labouAttendanceAccount) {
            $labouAttendanceAccountId = $labouAttendanceAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the attendance details. Try again after reloading the page!<small class='pull-right'> #04/04</small>")->with("alert-class","alert-danger")->with('controller_tab_flag', 'employee');
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
                //delete the transaction if associated attendance record saving failed.
                $transaction->delete();

                return redirect()->back()->withInput()->with("message","Failed to save the attendance details. Try again after reloading the page!<small class='pull-right'> #04/05</small>")->with("alert-class","alert-danger")->with('controller_tab_flag', 'employee');
            }
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the attendance details. Try again after reloading the page!<small class='pull-right'> #04/06</small>")->with("alert-class","alert-danger")->with('controller_tab_flag', 'employee');
        }
    }

    public function excavatorReadingsAction(ExcavatorReadingRegistrationRequest $request)
    {
        $rentTypeFlag       = 0;
        $excavatorName      = '';
        $date               = $request->get('excavator_date');
        $excavatorId        = $request->get('excavator_id');
        $bucketHour         = $request->get('excavator_bucket_hour');
        $breakerHour        = $request->get('excavator_breaker_hour');
        $operatorAccountId  = $request->get('excavator_operator_account_id');
        $operatorBata       = $request->get('excavator_operator_bata');

        $labouAttendanceAccount = Account::where('account_name','Labour Attendance')->first();
        if($labouAttendanceAccount) {
            $labouAttendanceAccountId = $labouAttendanceAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the excavator reading details. Try again after reloading the page!<small class='pull-right'> #04/18</small>")->with("alert-class","alert-danger")->with('controller_tab_flag', 'employee');
        }

        //converting date and time to sql datetime format
        $dateTime = date('Y-m-d H:i:s', strtotime($date.' '.'00:00:00'));
        $date = date('Y-m-d', strtotime($date.' '.'00:00:00'));

        $excavator = Excavator::where('id', $excavatorId)->first();
        if(!empty($excavator)) {
            $excavatorContractorAccountId = $excavator->contractor_account_id;
            $excavatorName  = $excavator->name;
            $rentType       = $excavator->rent_type;
            $bucketRate     = $excavator->rent_hourly_bucket;
            $breakerRate    = $excavator->rent_hourly_breaker;
            $bucketHour     = (!empty($bucketHour)) ? $bucketHour : 0;
            $breakerHour    = (!empty($breakerHour)) ? $breakerHour : 0;
            if($rentType == 'hourly'){
                $bill = ($bucketHour * $bucketRate) + ($breakerHour * $breakerRate);
            } else {
                $rentTypeFlag = 1;
            }
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the excavator reading details. Try again after reloading the page!<small class='pull-right'> #04/07</small>")->with("alert-class","alert-danger")->with('controller_tab_flag', 'excavator');
        }

        $recordFlag = ExcavatorReading::where('date',$date)->where('excavator_id', $excavatorId)->get();
        if(count($recordFlag) > 0) {
            return redirect()->back()->withInput()->with("message","Failed to save the excavator reading details. Reading of this excavator has already marked for ". date('d-m-Y', strtotime($date)). "!<small class='pull-right'> #04/08</small>")->with("alert-class","alert-danger")->with('controller_tab_flag', 'excavator');
        }

        $excavatorReadingAccount = Account::where('account_name','Excavator Reading')->first();
        if($excavatorReadingAccount) {
            $excavatorReadingAccountId = $excavatorReadingAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the excavator reading details. Try again after reloading the page!<small class='pull-right'> #04/09</small>")->with("alert-class","alert-danger")->with('controller_tab_flag', 'excavator');
        }

        $temp = ("Excavator Rent : Bucket : ".$bucketHour." * ".$bucketRate." = ".($bucketHour*$bucketRate)." / Breaker : ".$breakerHour." * ".$breakerRate." = ".($breakerHour * $breakerRate));

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
                $excavatorReading->date                 = $date;
                $excavatorReading->excavator_id         = $excavatorId;
                $excavatorReading->transaction_id       = $transaction->id;
                $excavatorReading->bucket_hour          = $bucketHour;
                $excavatorReading->breaker_hour         = $breakerHour;
                $excavatorReading->operator_account_id  = $operatorAccountId;
                $excavatorReading->bata                 = $operatorBata;
                $excavatorReading->bill_amount          = $bill;
                $excavatorReading->status               = 1;
            } else {
                return redirect()->back()->withInput()->with("message","Failed to save the excavator reading details. Try again after reloading the page!<small class='pull-right'> #04/10</small>")->with("alert-class","alert-danger")->with('controller_tab_flag', 'excavator');
            }
        } else {
            $excavatorReading = new ExcavatorReading;
            $excavatorReading->date                 = $date;
            $excavatorReading->excavator_id         = $excavatorId;
            $excavatorReading->bucket_hour          = $bucketHour;
            $excavatorReading->breaker_hour         = $breakerHour;
            $excavatorReading->operator_account_id  = $operatorAccountId;
            $excavatorReading->bata                 = $operatorBata;
            $excavatorReading->status               = 1;
        }
            
        if($excavatorReading->save()) {
            $bataTransaction = new Transaction;
            $bataTransaction->debit_account_id  = $labouAttendanceAccountId; //labour attendance account id
            $bataTransaction->credit_account_id = $operatorAccountId;
            $bataTransaction->amount            = $operatorBata;
            $bataTransaction->date_time         = $dateTime;
            $bataTransaction->particulars       = "Bata credited for " . $excavatorName;
            $bataTransaction->status            = 1;
            $bataTransaction->created_user_id   = Auth::user()->id;

            if($bataTransaction->save()) {
                return redirect()->back()->with("message","Successfully saved.")->with("alert-class","alert-success")->with('controller_tab_flag', 'excavator');
            } else {
                //delete the transaction if associated bata reading record saving failed.
                if($rentTypeFlag == 0) { //if the excavator rent type is hourly based.
                    //delete the transaction if associated excavator reading record saving failed.
                    $transaction->delete();
                }
                $excavatorReading->delete();
            }
        } else {
            if($rentTypeFlag == 0) { //if the excavator rent type is hourly based.
                //delete the transaction if associated excavator reading record saving failed.
                $transaction->delete();
            }
            return redirect()->back()->withInput()->with("message","Failed to save the excavator reading details. Try again after reloading the page!<small class='pull-right'> #04/11</small>")->with("alert-class","alert-danger")->with('controller_tab_flag', 'excavator');
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
                return redirect()->back()->withInput()->with("message","Failed to save the jackhammer reading details.<br>Selected jackhammer has a diffrent rent scheme!<small class='pull-right'> #04/12</small>")->with("alert-class","alert-danger")->with('controller_tab_flag', 'jackhammer');
            }
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the jackhammer reading details.Try again after reloading the page!<small class='pull-right'> #04/13</small>")->with("alert-class","alert-danger")->with('controller_tab_flag', 'jackhammer');
        }

        $recordFlag = JackhammerReading::where('date',$date)->where('jackhammer_id', $jackhammerId)->get();
        if(count($recordFlag) > 0) {
            return redirect()->back()->withInput()->with("message","Failed to save the jackhammer reading details.<br>Reading of this jackhammer has already marked for ".date('d-m-Y', strtotime($date)). "!<small class='pull-right'> #04/14</small>")->with("alert-class","alert-danger")->with('controller_tab_flag', 'jackhammer');
        }

        $jackhammerReadingAccount = Account::where('account_name','Jackhammer Reading')->first();
        if($jackhammerReadingAccount) {
            $jackhammerReadingAccountId = $jackhammerReadingAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the jackhammer reading details.Try again after reloading the page!<small class='pull-right'> #04/15</small>")->with("alert-class","alert-danger")->with('controller_tab_flag', 'jackhammer');
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
            $jackhammerReading->bill_amount      = $bill;
            $jackhammerReading->status           = 1;

            if($jackhammerReading->save()) {
                return redirect()->back()->with("message","Successfully saved.")->with("alert-class","alert-success")->with('controller_tab_flag', 'jackhammer');
            } else {
                //delete the transaction if associated jackhammer reading record saving failed.
                $transaction->delete();

                return redirect()->back()->withInput()->with("message","Failed to save the jackhammer reading details.Try again after reloading the page!<small class='pull-right'> #04/16</small>")->with("alert-class","alert-danger")->with('controller_tab_flag', 'jackhammer');
            }
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the jackhammer reading details.Try again after reloading the page!<small class='pull-right'> #04/17</small>")->with("alert-class","alert-danger")->with('controller_tab_flag', 'jackhammer');
        }
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
            $query = $query->whereHas('transaction', function ($qry) use($accountId) {
                $qry->where('credit_account_id', $accountId);
            });
        }

        if(!empty($employeeId) && $employeeId != 0) {
            $query = $query->where('employee_id', $employeeId);
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

        $totalQuery     = clone $query;
        $totalAmount    = $totalQuery->sum('wage');

        $employeeAttendance = $query->with(['employee.account.accountDetail'])->orderBy('date','desc')->paginate(15);
        
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
                'totalAmount'           => $totalAmount
            ]);
    }

    /**
     * Return view for daily statement listing / excavator reading
     */
    public function excavatorReadingList(Request $request)
    {
        $totalAmount    = 0;
        $totalBucketReading     = 0;
        $totalBreakerReading    = 0;
        $totalBata              = 0;
        /*$bucketRate             = 0;
        $breakerRate            = 0;*/
        $accountId      = !empty($request->get('excavator_account_id')) ? $request->get('excavator_account_id') : 0;
        $excavatorId    = !empty($request->get('excavator_id')) ? $request->get('excavator_id') : 0;
        $fromDate       = !empty($request->get('excavator_from_date')) ? $request->get('excavator_from_date') : '';
        $toDate         = !empty($request->get('excavator_to_date')) ? $request->get('excavator_to_date') : '';

        $accounts   = Account::where('type', 'personal')->where('status', '1')->get();
        $excavators = Excavator::with(['account.accountDetail'])->where('status', '1')->get();

        $query = ExcavatorReading::where('status', 1);

        if(!empty($accountId) && $accountId != 0) {
            $query = $query->whereHas('transaction', function ($qry) use($accountId) {
                $qry->where('credit_account_id', $accountId);
            });
        }

        if(!empty($excavatorId) && $excavatorId != 0) {
            $query = $query->where('excavator_id', $excavatorId);
            /*$excavator = Excavator::find($excavatorId);
            $bucketRate     = $excavator->rent_hourly_bucket;
            $breakerRate    = $excavator->rent_hourly_breaker;*/
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

        $totalQuery     = clone $query;
        $totalAmount    = $totalQuery->sum('bill_amount');

        $totalBucketReadingQuery    = clone $query;
        $totalBucketReading         = $totalBucketReadingQuery->sum('bucket_hour');

        $totalBreakerReadingQuery   = clone $query;
        $totalBreakerReading        = $totalBreakerReadingQuery->sum('breaker_hour');

        $totalBataQuery   = clone $query;
        $totalBata        = $totalBataQuery->sum('bata');        

        $excavatorReadings = $query->with(['excavator.account.accountDetail'])->orderBy('date','desc')->paginate(15);
        
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
                'totalAmount'           => $totalAmount,
                'totalBreakerReading'   => $totalBreakerReading,
                'totalBucketReading'    => $totalBucketReading,
                'totalBata'             => $totalBata,
                /*'breakerRate'           => $breakerRate,
                'bucketRate'            => $bucketRate*/
            ]);
    }

    /**
     * Return view for daily statement listing / daily statement
     */
    public function jackhammerReadingList(Request $request)
    {
        $totalAmount    = 0;
        $totalDepth     = 0;
        $jackhammerRate = 0;
        $accountId      = !empty($request->get('jackhammer_account_id')) ? $request->get('jackhammer_account_id') : 0;
        $jackhammerId   = !empty($request->get('jackhammer_id')) ? $request->get('jackhammer_id') : 0;
        $fromDate       = !empty($request->get('jackhammer_from_date')) ? $request->get('jackhammer_from_date') : '';
        $toDate         = !empty($request->get('jackhammer_to_date')) ? $request->get('jackhammer_to_date') : '';

        $accounts    = Account::where('type', 'personal')->where('status', '1')->get();
        $jackhammers = Jackhammer::with(['account.accountDetail'])->where('status', '1')->get();

        $query = JackhammerReading::where('status', 1);

        if(!empty($accountId) && $accountId != 0) {
            $query = $query->whereHas('transaction', function ($qry) use($accountId) {
                $qry->where('credit_account_id', $accountId);
            });
        }

        if(!empty($jackhammerId) && $jackhammerId != 0) {
            $query = $query->where('jackhammer_id', $jackhammerId);

            $jackhammer = Jackhammer::find($jackhammerId);
            $jackhammerRate     = $jackhammer->rent_feet;
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

        $totalQuery     = clone $query;
        $totalAmount    = $totalQuery->sum('bill_amount');

        $totalDepthQuery   = clone $query;
        $totalDepth        = $totalDepthQuery->sum('total_pit_depth'); 

        $jackhammerReadings = $query->with(['jackhammer.account.accountDetail'])->orderBy('date','desc')->paginate(15);
        
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
                'totalAmount'           => $totalAmount,
                'totalDepth'            => $totalDepth,
                'jackhammerRate'        => $jackhammerRate
            ]);
    }

    /**
     * Return view for daily statement
     */
    public function dailySatementSearch(Request $request)
    {
        $sales                  = 0;
        $purchases              = 0;
        $labourWage             = 0;
        $excavatorReadingRent   = 0;
        $jackhammerRent         = 0;
        $employeeSalary         = 0;
        $excavatorMonthlyRent   = 0;
        $royalty                = 0;
        $fromDate               = 0;
        $toDate                 = 0;
        $totalDebit             = 0;
        $totalCredit            = 0;
        $shareButtonFlag        = false;
        $restrictedDate         = "";

        $lastRecord     = ProfitLoss::where('status', 1)->orderBy('to_date', 'desc')->first();

        if(!empty($lastRecord) && !empty($lastRecord->id))
        {
            $restrictedDate = Carbon::createFromFormat('Y-m-d', $lastRecord->to_date);
        } 

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

            if(($searchFromDate->dayOfWeek == Carbon::SUNDAY) && ($searchFromDate->diffInDays($searchToDate) == 6)) {
                $shareButtonFlag = true;
            }
        }

        $salesQuery = clone $query;
        $sales = $salesQuery->where('credit_account_id', 2)->sum('amount');

        $purchasesQuery = clone $query;
        $purchases = $purchasesQuery->where('debit_account_id', 3)->sum('amount');

        $labourWageQuery = clone $query;
        $labourWage = $labourWageQuery->where('debit_account_id', 4)->sum('amount');

        $excavatorReadingRentQuery = clone $query;
        $excavatorReadingRent = $excavatorReadingRentQuery->where('debit_account_id', 5)->sum('amount');

        $jackhammerRentQuery = clone $query;
        $jackhammerRent = $jackhammerRentQuery->where('debit_account_id', 6)->sum('amount');

        $employeeSalaryQuery = clone $query;
        $employeeSalary = $employeeSalaryQuery->where('debit_account_id', 7)->sum('amount');

        $excavatorMonthlyRentQuery = clone $query;
        $excavatorMonthlyRent = $excavatorMonthlyRentQuery->where('debit_account_id', 8)->sum('amount');

        $royaltyQuery = clone $query;
        $royalty = $royaltyQuery->where('debit_account_id', 9)->sum('amount');

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
                'totalCredit'           => $totalCredit,
                'shareButtonFlag'       => $shareButtonFlag,
                'restrictedDate'        => $restrictedDate
            ]);
    }

    /**
     * Handle employee attendance delete action
     */
    public function employeeAttendanceDeleteAction(DeleteAttendanceRequest $request)
    {
        $id     = $request->get('attenddance_id');
        $date   = $request->get('date');
        
        $attendance = EmployeeAttendance::where('id', $id)->where('status', 1)->first();

        if(!empty($attendance) && !empty($attendance->id)) {
            if(Carbon::parse($attendance->date)->format('d-m-Y') != $date) {
                return redirect()->back()->with("message","Deletion restricted. Date change detected!! #04/19")->with("alert-class","alert-danger");
            }

            if($attendance->transaction->created_user_id != Auth::id() && Auth::user()->role != 'admin') {
                return redirect()->back()->with("message","Failed to delete the attendance details.You don't have the permission to delete this record! #04/20")->with("alert-class","alert-danger");
            }
            if($attendance->transaction->created_at->diffInDays(Carbon::now(), false) > 5) {
                return redirect()->back()->with("message","Deletion restricted.Only records created within 5 days can be deleted! #04/21")->with("alert-class","alert-danger");
            }

            $attendanceTransactionDelete    = $attendance->transaction->delete();
            $attendanceDeleteFlag           = $attendance->delete();

            if($attendanceTransactionDelete && $attendanceDeleteFlag) {
                return redirect()->back()->with("message","#". $attendance->transaction->id. " -Successfully deleted.")->with("alert-class","alert-success");
            }
        }

        return redirect()->back()->with("message","Failed to delete the attendance details.Try again after reloading the page! #04/22")->with("alert-class","alert-danger");
    }

    /**
     * Handle excavator reading delete action
     */
    public function excavatorReadingDeleteAction(DeleteExcavatorReadingRequest $request)
    {
        $id     = $request->get('excavator_id');
        $date   = $request->get('date');
        
        $reading = ExcavatorReading::where('id', $id)->where('status', 1)->first();

        if(!empty($reading) && !empty($reading->id)) {
            if(Carbon::parse($reading->date)->format('d-m-Y') != $date) {
                return redirect()->back()->with("message","Deletion restricted. Date change detected!! #04/23")->with("alert-class","alert-danger");
            }

            if($reading->transaction->created_user_id != Auth::id() && Auth::user()->role != 'admin') {
                return redirect()->back()->with("message","Failed to delete the reading details.You don't have the permission to delete this record! #04/24")->with("alert-class","alert-danger");
            }
            if($reading->transaction->created_at->diffInDays(Carbon::now(), false) > 5) {
                return redirect()->back()->with("message","Deletion restricted.Only records created within 5 days can be deleted! #04/25")->with("alert-class","alert-danger");
            }

            $readingTransactionDelete   = $reading->transaction->delete();
            $readingDeleteFlag          = $reading->delete();

            if($readingTransactionDelete && $readingDeleteFlag) {
                return redirect()->back()->with("message","#". $reading->transaction->id. " -Successfully deleted.")->with("alert-class","alert-success");
            }
        }

        return redirect()->back()->with("message","Failed to delete the reading details.Try again after reloading the page! #04/26")->with("alert-class","alert-danger");
    }

    /**
     * Handle excavator reading delete action
     */
    public function jackhammerReadingDeleteAction(DeleteJackhammerReadingRequest $request)
    {
        $id     = $request->get('jackhammer_id');
        $date   = $request->get('date');
        
        $reading = JackhammerReading::where('id', $id)->where('status', 1)->first();

        if(!empty($reading) && !empty($reading->id)) {
            if(Carbon::parse($reading->date)->format('d-m-Y') != $date) {
                return redirect()->back()->with("message","Deletion restricted. Date change detected!! #04/27")->with("alert-class","alert-danger");
            }

            if($reading->transaction->created_user_id != Auth::id() && Auth::user()->role != 'admin') {
                return redirect()->back()->with("message","Failed to delete the reading details.You don't have the permission to delete this record! #04/28")->with("alert-class","alert-danger");
            }
            if($reading->transaction->created_at->diffInDays(Carbon::now(), false) > 5) {
                return redirect()->back()->with("message","Deletion restricted.Only records created within 5 days can be deleted! #04/29")->with("alert-class","alert-danger");
            }

            $readingTransactionDelete   = $reading->transaction->delete();
            $readingDeleteFlag          = $reading->delete();

            if($readingTransactionDelete && $readingDeleteFlag) {
                return redirect()->back()->with("message","#". $reading->transaction->id. " -Successfully deleted.")->with("alert-class","alert-success");
            }
        }

        return redirect()->back()->with("message","Failed to delete the reading details.Try again after reloading the page! #04/30")->with("alert-class","alert-danger");
    }
}