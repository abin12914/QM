<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EmployeeRegistrationRequest;
use App\Models\Account;
use App\Models\AccountDetail;
use App\Models\Employee;
use App\Models\EmployeeSalary;
use DateTime;

class EmployeeController extends Controller
{
    /**
     * Return view for employee registration
     */
    public function register()
    {
        return view('employee.register');
    }

     /**
     * Handle new employee registration
     */
    public function registerAction(EmployeeRegistrationRequest $request)
    {
        $destination        = '/images/employee/'; // image file upload path
        $flag = 0;

        $name               = $request->get('name');
        $phone              = $request->get('phone');
        $address            = $request->get('address');
        $employeeType       = $request->get('employee_type');
        $salary             = $request->get('salary');
        $wage               = $request->get('wage');
        $accountName        = $request->get('account_name');
        $financialStatus    = $request->get('financial_status');
        $openingBalance     = $request->get('opening_balance');

        $openingBalanceAccount = Account::where('account_name','Account Opening Balance')->first();
        if(!empty($openingBalanceAccount) && !empty($openingBalanceAccount->id)) {
            $openingBalanceAccountId = $openingBalanceAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the employee details. Try again after reloading the page!<small class='pull-right'> Error Code :08/01</small>")->with("alert-class","alert-danger");
        }

        if ($request->hasFile('image_file')) {
            $file               = $request->file('image_file');
            $extension          = $file->getClientOriginalExtension(); // getting image extension
            $fileName           = $name.'_'.time().'.'.$extension; // renameing image
            $file->move(public_path().$destination, $fileName); // uploading file to given path
        }

        if($employeeType == 'staff') {
            $description = "Category : Staff. Payment mode : Monthly";
        } else {
            $description = "Category : Labour.  Payment mode : Daily";
        }

        if(!empty($fileName)) {
            $image   = $destination.$fileName;
        } else {
            $image   = $destination."default_employee.jpg";
        }

        $account = new Account;
        $account->account_name      = $accountName;
        $account->description       = $description;
        $account->type              = "personal";
        $account->relation          = 'employee';
        $account->financial_status  = $financialStatus;
        $account->opening_balance   = $openingBalance;
        $account->status            = 1;
        if($account->save()) {
            $accountDetail = new AccountDetail;
            $accountDetail->account_id  = $account->id;
            $accountDetail->name        = $name;
            $accountDetail->phone       = $phone;
            $accountDetail->address     = $address;
            $accountDetail->image       = $image;
            $accountDetail->status      = 1;

            if($accountDetail->save()){
                $employee = new Employee;
                $employee->employee_type    = $employeeType;
                $employee->salary           = $salary;
                $employee->wage             = $wage;
                $employee->account_id       = $account->id;
                $employee->status           = 1;

                if($employee->save()){
                    if($financialStatus == 'debit') {//incoming [account holder gives cash to company, after this transaction company owes account holder] [Creditor]
                        $debitAccountId     = $openingBalanceAccountId;
                        $creditAccountId    = $account->id;
                        $particulars        = "Opening balance of ". $name . " - Debit [Creditor]";
                    } else if($financialStatus == 'credit'){//outgoing [company gives cash to account holder] [after this transaction account holder owes company] [Debitor]
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
                        $employee->delete();

                        $saveFlag = 2;
                    }
                } else {
                    //delete the account, account detail if employee saving failed
                    $account->delete();
                    $accountDetails->delete();

                    $flag = 3;
                }
            } else {
                //delete the account if account detail saving failed
                $account->delete();

                $flag = 4;
            }
        } else {
            $flag = 5;
        }

        if($flag == 1) {
            return redirect()->back()->with("message","Successfully Saved.")->with("alert-class","alert-success");
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the employee details. Try again after reloading the page!<small class='pull-right'> Error Code :08/02/". $flag ."</small>")->with("alert-class","alert-danger");
        }
    }

    /**
     * Return view for employee listing
     */
    public function list(Request $request)
    {
        $accountId  = !empty($request->get('account_id')) ? $request->get('account_id') : 0;
        $employeeId = !empty($request->get('employee_id')) ? $request->get('employee_id') : 0;
        $type       = !empty($request->get('type')) ? $request->get('type') : '0';

        $accounts = Account::where('relation', 'employee')->where('status', '1')->get();
        $employeesCombobox = Employee::with('account.accountDetail')->where('status', '1')->get();

        $query = Employee::where('status', '1');

        if(!empty($accountId) && $accountId != 0) {
            $query = $query->where('account_id', $accountId);
        }

        if(!empty($employeeId) && $employeeId != 0) {
            $query = $query->where('id', $employeeId);
        }

        if(!empty($type) && $type != '0') {
            $query = $query->where('employee_type', $type);
        }

        $employees = $query->with('account.accountDetail')->orderBy('created_at','desc')->paginate(10);

        return view('employee.list',[
                'accounts'          => $accounts,
                'employeesCombobox' => $employeesCombobox,
                'employees'         => $employees,
                'accountId'         => $accountId,
                'employeeId'        => $employeeId,
                'type'              => $type
            ]);
    }

    /**
     * Return employee for given account id
     */
    public function getEmployeeByaccountId($accountId)
    {
        $employee = Employee::where('account_id', $accountId)->first();
        if(!empty($employee) && !empty($employee->id)) {
            $employeeId = $employee->id;
            $employeeSalary = EmployeeSalary::where('employee_id', $employeeId)->orderBy('to_date','desc')->first();
            if(!empty($employeeSalary) && !empty($employeeSalary->id)) {
                $employeeLastSalaryDate = $employeeSalary->to_date;
                $salaryDate = new DateTime($employeeLastSalaryDate);
                $salaryDate->modify('+1 day');
                $salaryDate = $salaryDate->format('m-d-Y');
            }
            
            return ([
                    'flag'          => true,
                    'employeeId'    => $employee->id,//account->accountDetail->name,
                    'wage'          => ($employee->employee_type == 'labour') ? $employee->wage : $employee->salary,
                    'salaryDate'    => !empty($salaryDate) ? $salaryDate : ''
                ]);
        } else {
            return ([
                    'flag'      => false
                ]);            
        }
    }

    /**
     * Return employee account for given employee id
     */
    public function getEmployeeByEmployeeId($employeeId)
    {
        $employee = Employee::where('id', $employeeId)->first();
        if(!empty($employee) && !empty($employee->id)) {
            $employeeId = $employee->id;
            $employeeSalary = EmployeeSalary::where('employee_id', $employeeId)->orderBy('to_date','desc')->first();
            if(!empty($employeeSalary) && !empty($employeeSalary->id)) {
                $employeeLastSalaryDate = $employeeSalary->to_date;
                $salaryDate = new DateTime($employeeLastSalaryDate);
                $salaryDate->modify('+1 day');
                $salaryDate = $salaryDate->format('m-d-Y');
            }

            return ([
                    'flag'       => true,
                    'accountId'  => $employee->account_id,
                    'wage'       => ($employee->employee_type == 'labour') ? $employee->wage : $employee->salary,
                    'salaryDate' => !empty($salaryDate) ? $salaryDate : ''
                ]);
        } else {
            return ([
                    'flag'      => false
                ]);            
        }
    }
}
