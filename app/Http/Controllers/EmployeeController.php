<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EmployeeRegistrationRequest;
use App\Models\Account;
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
        $destination        = '/images/labour/'; // image file upload path
        $flag =0;

        $name               = $request->get('name');
        $phone              = $request->get('phone');
        $address            = $request->get('address');
        $employeeType       = $request->get('employee_type');
        $salary             = $request->get('salary');
        $wage               = $request->get('wage');
        $financialStatus    = $request->get('financial_status');
        $openingBalance     = $request->get('opening_balance');

        if ($request->hasFile('image_file')) {
            $file               = $request->file('image_file');
            $extension          = $file->getClientOriginalExtension(); // getting image extension
            $fileName           = $name.'_'.time().'.'.$extension; // renameing image
            $file->move(public_path().$destination, $fileName); // uploading file to given path
        }

        $account = new Account;
        $account->name              = $name;
        $account->description       = "Staff of the organization";
        $account->type              = "staff";
        $account->financial_status  = $financialStatus;
        $account->opening_balance   = $openingBalance;
        $account->status            = 1;
        if($account->save()) {
            $staff = new Employee;
            $staff->name        = $name;
            $staff->phone       = $phone;
            $staff->address     = $address;
            if(!empty($fileName)) {
                $staff->image   = $destination.$fileName;
            } else {
                $staff->image   = $destination."default_staff.jpg";
            }
            $staff->salary      = $salary;
            $staff->account_id  = $account->id;
            $staff->status      =1;
            if($staff->save()){
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
