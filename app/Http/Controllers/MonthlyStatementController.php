<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use App\Models\Account;
use App\Models\Employee;
use App\Models\EmployeeSalary;
use App\Models\Transaction;
use App\Models\Excavator;
use App\Models\ExcavatorRent;
use App\Http\Requests\EmployeeSalaryRegistrationRequest;
use App\Http\Requests\ExcavatorRentRegistrationRequest;

class MonthlyStatementController extends Controller
{
    /**
     * Return view for monthly statement registration
     */
    public function register()
    {
        $employeeAccounts   = Account::where('relation','employee')->get();
        $employees          = Employee::get();
        $excavators         = Excavator::where('rent_type', 'monthly')->get();

        if(!empty($employeeAccounts) && !empty($employees) && !empty($excavators)) {
            return view('monthly-statement.register',[
                    'employeeAccounts'   => $employeeAccounts,
                    'employees'          => $employees,
                    'excavators'         => $excavators,
                ]);
        } else {
            return view('daily-statement.register',[]);
        }
    }

    /**
     * Handle salary of the employee
     */
    public function employeeSalaryAction(EmployeeSalaryRegistrationRequest $request)
    {
        $employeeId         = $request->get('emp_salary_employee_id');
        $employeeAccountId  = $request->get('emp_salary_account_id');
        $startDate          = $request->get('emp_salary_start_date');
        $endDate            = $request->get('emp_salary_end_date');
        $salary             = $request->get('emp_salary_salary');
        $description        = $request->get('emp_salary_description');

        //converting date and time to sql datetime format
        $dateTime   = date('Y-m-d H:i:s', strtotime('now'));
        $startDate  = date('Y-m-d', strtotime($startDate.' '.'00:00:00'));
        $endDate    = date('Y-m-d', strtotime($endDate.' '.'00:00:00'));

        $employee = Employee::where('account_id', $employeeAccountId)->first();
        if(!empty($employee) && $employeeId == $employee->id) {
            $employeeName = $employee->account->accountDetail->name;
            if($employee->employee_type == "labour") {
                return redirect()->back()->with("message","Selected employee has daily wage scheme; Use daily statement for this user.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'employee');
            }
        } else {
            return redirect()->back()->with("message","Something went wrong! Employee not found.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'employee');
        }

        $recordFlag = EmployeeSalary::where('employee_id', $employeeId)->where(function ($query) use($startDate, $endDate) {
            $query->whereBetween('from_date',[$startDate, $endDate])->orWhereBetween('to_date',[$startDate, $endDate])->orWhere(function ($query) use($startDate, $endDate) {
            $query->where('to_date', '>=', $startDate)->where('to_date', '<=', $endDate);
        });
        })->get();
        /*$recordFlag2 = EmployeeSalary::where('employee_id', $employeeId)->where(function ($query) use($startDate, $endDate) {
            $query->where('to_date', '>=', $startDate)->where('to_date', '<=', $endDate);
        })->get();*/
        //$recordFlag = EmployeeSalary::where('employee_id', $employeeId)->where('to_date', '<=', $startDate)->get();
        if(count($recordFlag) > 0) {
            return redirect()->back()->with("message","Error! Salary of this user for the selected date period has already generated.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'employee');
        }

        $employeeSalaryAccount = Account::where('account_name','Employee Salary')->first();
        if($employeeSalaryAccount) {
            $employeeSalaryAccountId = $employeeSalaryAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Employee salary account not found.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'employee');
        }

        $transaction = new Transaction;
        $transaction->debit_account_id  = $employeeSalaryAccountId; //employee salary account id
        $transaction->credit_account_id = $employeeAccountId;
        $transaction->amount            = !empty($salary) ? $salary : '0';
        $transaction->date_time         = $dateTime;
        $transaction->particulars       = "Employee salary generated for ".$employeeName." from ".$startDate." to ".$endDate;
        $transaction->status            = 1;
        $transaction->created_user_id   = Auth::user()->id;

        if($transaction->save()) {
            $employeeSalary = new EmployeeSalary;
            $employeeSalary->employee_id    = $employeeId;
            $employeeSalary->transaction_id = $transaction->id;
            $employeeSalary->from_date      = $startDate;
            $employeeSalary->to_date        = $endDate;
            $employeeSalary->salary         = $salary;
            $employeeSalary->status         = 1;
            
            if($employeeSalary->save()) {
                return redirect()->back()->with("message","Successfully saved.")->with("alert-class","alert-success")->with('controller_tab_flag', 'employee');
            } else {
                return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the salary details. Try after reloading the page.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'employee');
            }
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the salary details. Try after reloading the page.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'employee');
        }
    }

    public function excavatorRentAction(ExcavatorRentRegistrationRequest $request)
    {
        $excavatorId    = $request->get('excavator_id');
        $fromDate       = $request->get('excavator_from_date');
        $toDate         = $request->get('excavator_to_date');
        $rent           = $request->get('excavator_rent');
        $description    = $request->get('excavator_description');

        //converting date and time to sql datetime format
        $dateTime  = date('Y-m-d H:i:s', strtotime('now'));
        $fromDate  = date('Y-m-d', strtotime($fromDate.' '.'00:00:00'));
        $toDate    = date('Y-m-d', strtotime($toDate.' '.'00:00:00'));

        $excavator = Excavator::where('id', $excavatorId)->first();
        if(!empty($excavator) && !empty($excavator->id)) {
            $excavatorName = $excavator->name;
            if($excavator->rent_type == 'monthly'){
                $excavatorContractorAccountId = $excavator->contractor_account_id;
            } else {
                return redirect()->back()->with("message","Selected excavator has hourly rental scheme; Use daily statement for this machine.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'employee');    
            }
        } else {
            return redirect()->back()->with("message","Something went wrong! Excavator not found.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'excavator');
        }

        $recordFlag = ExcavatorRent::where('excavator_id', $excavatorId)->where(function ($query) use($fromDate, $toDate) {
            $query->whereBetween('from_date',[$fromDate, $toDate])->orWhereBetween('to_date',[$fromDate, $toDate])->orWhere(function ($query) use($fromDate, $toDate) {
            $query->where('to_date', '>=', $fromDate)->where('to_date', '<=', $toDate);
        });
        })->get();

        if(count($recordFlag) > 0) {
            return redirect()->back()->with("message","Error! Rent of this excavator for the selected period has already allotted.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'excavator');
        }

        $excavatorRentAccount = Account::where('account_name','Excavator Rent')->first();
        if($excavatorRentAccount) {
            $excavatorRentAccountId = $excavatorRentAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Excavator rent account not found.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'excavator');
        }

        $transaction = new Transaction;
        $transaction->debit_account_id  = $excavatorRentAccountId; //excavator rent account id
        $transaction->credit_account_id = $excavatorContractorAccountId;
        $transaction->amount            = $rent;
        $transaction->date_time         = $dateTime;
        $transaction->particulars       = "Excavator rent generated for ".$excavatorName." from ".$fromDate." to ".$toDate;;
        $transaction->status            = 1;
        $transaction->created_user_id   = Auth::user()->id;

        if($transaction->save()) {
            $excavatorRent = new ExcavatorRent;
            $excavatorRent->excavator_id     = $excavatorId;
            $excavatorRent->transaction_id   = $transaction->id;
            $excavatorRent->from_date        = $fromDate;
            $excavatorRent->to_date          = $toDate;
            $excavatorRent->rent             = $rent;
            $excavatorRent->status           = 1;

            if($excavatorRent->save()) {
                return redirect()->back()->with("message","Successfully saved.")->with("alert-class","alert-success")->with('controller_tab_flag', 'excavator');
            } else {
                return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the rent details. Try after reloading the page.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'excavator');
            }
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the rent details. Try after reloading the page.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'excavator');
        }
    }

    /**
     * Return view for monthly statement listing / employee salary
     */
    public function employeeSalaryList()
    {
        $employeeSalary = EmployeeSalary::paginate(10);

        if(empty($employeeSalary)) {
            $employeeSalary = [];
        }
        return view('monthly-statement.list',[
                    'employeeSalary'    => $employeeSalary,
                    'excavatorRent'     => []
                ]);
    }

    /**
     * Return view for monthly statement listing / excavator rent
     */
    public function excavatorRentList()
    {
        $excavatorRent  = ExcavatorRent::paginate(10);

        if(empty($excavatorRent)) {
            $excavatorRent = [];
        }
        return view('monthly-statement.list',[
                'employeeSalary'    => [],
                'excavatorRent'     => $excavatorRent,
            ]);
    }
}
