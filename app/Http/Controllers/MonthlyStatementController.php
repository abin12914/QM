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
use App\Http\Requests\EmployeeSalaryRegistrationRequest;

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
            if($employee->employee_type == "labour") {
                return redirect()->back()->with("message","Selected employee has daily wage scheme; Use daily statement for this user.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'employee');    
            }
        } else {
            return redirect()->back()->with("message","Something went wrong! Employee not found.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'employee');
        }

        $recordFlag = EmployeeSalary::where('employee_id', $employeeId)->whereBetween('to_date',[$startDate, $endDate])->get();
        if(count($recordFlag) > 0) {
            return redirect()->back()->with("message","Error! Salary of this user for the selected date has already marked.")->with("alert-class","alert-danger")->with('controller_tab_flag', 'employee');
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
        $transaction->particulars       = "Employee salary generated for ".$startDate." - ".$endDate;
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
}
