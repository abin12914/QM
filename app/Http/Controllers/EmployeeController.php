<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EmployeeRegistrationRequest;
use App\Models\Account;
use App\Models\AccountDetail;
use App\Models\Employee;

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
        $flag =0;

        $name               = $request->get('name');
        $phone              = $request->get('phone');
        $address            = $request->get('address');
        $employeeType       = $request->get('employee_type');
        $salary             = $request->get('salary');
        $wage               = $request->get('wage');
        $accountName        = $request->get('account_name');
        $financialStatus    = $request->get('financial_status');
        $openingBalance     = $request->get('opening_balance');

        if ($request->hasFile('image_file')) {
            $file               = $request->file('image_file');
            $extension          = $file->getClientOriginalExtension(); // getting image extension
            $fileName           = $name.'_'.time().'.'.$extension; // renameing image
            $file->move(public_path().$destination, $fileName); // uploading file to given path
        }

        $account = new Account;
        $account->account_name          = $accountName;
        if($employeeType == 'staff') {
            $account->description       = "Category : Staff. Payment mode : Monthly";
        } else {
            $account->description       = "Category : Labour.  Payment mode : Daily";
        }
        $account->type              = "personal";
        $account->relation          = "employee";
        $account->financial_status  = $financialStatus;
        $account->opening_balance   = $openingBalance;
        $account->status            = 1;
        if($account->save()) {
            $employee = new Employee;
            $employee->employee_type    = $employeeType;
            $employee->salary           = $salary;
            $employee->wage             = $wage;
            $employee->account_id       = $account->id;
            $employee->status           = 1;

            if($employee->save()){
                $flag =0;
            } else {
                $flag = 3;
            }

            $accountDetail = new AccountDetail;
            $accountDetail->account_id  = $account->id;
            $accountDetail->name        = $name;
            $accountDetail->phone       = $phone;
            $accountDetail->address     = $address;
            if(!empty($fileName)) {
                $accountDetail->image   = $destination.$fileName;
            } else {
                $accountDetail->image   = $destination."default_employee.jpg";
            }
            $accountDetail->status      = 1;
            if($accountDetail->save()){
                $flag =0;
            } else {
                $flag = 2;
            }
        } else {
            $flag = 1;
        }

        if($flag == 0) {
            return redirect()->back()->with("message","Staff details saved successfully.")->with("alert-class","alert-success");
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the staff data. Try after reloading the page. Error code : 00".$flag)->with("alert-class","alert-danger");
        }
    }
}