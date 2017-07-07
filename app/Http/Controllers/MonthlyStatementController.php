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

class MonthlyStatementController extends Controller
{
    /**
     * Return view for monthly statement registration
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
            return view('monthly-statement.register',[
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
}
