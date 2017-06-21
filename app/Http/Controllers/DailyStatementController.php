<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
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
        if(empty($employee) || $employeeId != $employee->id) {
            return redirect()->back()->with("message","Something went wrong! Employee not found.")->with("alert-class","alert-danger");
        }

        $recordFlag = EmployeeAttendance::where('date',$date)->where('employee_id', $employeeId)->get();
        if(count($recordFlag) > 0) {
            return redirect()->back()->with("message","Error! Attendance of this user has already marked for ".$date)->with("alert-class","alert-danger");
        }

        $labouAttendanceAccount = Account::where('account_name','Labour Attendance')->first();
        if($labouAttendanceAccount) {
            $labouAttendanceAccountId = $labouAttendanceAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Labour attendance account not found.")->with("alert-class","alert-danger");
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
                return redirect()->back()->with("message","Successfully saved.")->with("alert-class","alert-success");
            } else {
                return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the attendance details. Try after reloading the page.")->with("alert-class","alert-danger");
            }
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the attendance details. Try after reloading the page.")->with("alert-class","alert-danger");
        }
    }

    public function excavatorReadingsAction(ExcavatorReadingRegistrationRequest $request)
    {
        $date               = $request->get('attendance_date');
        $employeeId         = $request->get('attendance_employee_id');
        $employeeAccountId  = $request->get('attendance_account_id');
        $wage               = $request->get('attendance_wage');
dd('x');
        //converting date and time to sql datetime format
        $dateTime = date('Y-m-d H:i:s', strtotime($date.' '.'00:00:00'));
        $date = date('Y-m-d', strtotime($date.' '.'00:00:00'));

        $employee = Employee::where('account_id', $employeeAccountId)->first();
        if(empty($employee) || $employeeId != $employee->id) {
            return redirect()->back()->with("message","Something went wrong! Employee not found.")->with("alert-class","alert-danger");
        }

        $recordFlag = EmployeeAttendance::where('date',$date)->where('employee_id', $employeeId)->get();
        if(count($recordFlag) > 0) {
            return redirect()->back()->with("message","Error! Attendance of this user has already marked for the day.")->with("alert-class","alert-danger");
        }

        $labouAttendanceAccount = Account::where('account_name','Labour Attendance')->first();
        if($labouAttendanceAccount) {
            $labouAttendanceAccountId = $labouAttendanceAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Labour attendance account not found.")->with("alert-class","alert-danger");
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
                return redirect()->back()->with("message","Successfully saved.")->with("alert-class","alert-success");
            } else {
                return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the attendance details. Try after reloading the page.")->with("alert-class","alert-danger");
            }
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the attendance details. Try after reloading the page.")->with("alert-class","alert-danger");
        }
    }
}
