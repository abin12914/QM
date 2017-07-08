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
        $employeeAccounts   = Account::where('relation','employee')->get();
        $employees          = Employee::get();
        $excavators         = Excavator::get();
        $jackhammers        = Jackhammer::get();

        if(!empty($employeeAccounts) && !empty($employees) && !empty($excavators) && !empty($jackhammers)) {
            return view('monthly-statement.register',[
                    'employeeAccounts'   => $employeeAccounts,
                    'employees'          => $employees,
                    'excavators'         => $excavators,
                    'jackhammers'        => $jackhammers,
                ]);
        } else {
            return view('daily-statement.register',[
                    'today' => $today
                ]);
        }
    }
}
