<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExcavatorRegistrationRequest;
use App\Models\Excavator;
use App\Models\Account;
use App\Models\ExcavatorRent;
use DateTime;

class ExcavatorController extends Controller
{
    /**
     * Return view for excavator registration
     */
    public function register()
    {   
        $accounts = Account::where('type','personal')->orderBy('account_name')->get();
    	return view('excavator.register',[
            'accounts' => $accounts,
        ]);
    }

     /**
     * Handle new excavator registration
     */
    public function registerAction(ExcavatorRegistrationRequest $request)
    {
        $name                   = $request->get('name');
        $description            = $request->get('description');
        $contractorAccountId    = $request->get('contractor_account_id');
        $rentalType             = $request->get('rent_type');
        $rentMonthly            = ($rentalType == "monthly") ? ($request->get('rate_monthly')) : 0;
        $rentHourlyBucket       = ($rentalType == "hourly") ? ($request->get('rate_bucket')) : 0;
        $rentHourlyBreaker      = ($rentalType == "hourly") ? ($request->get('rate_breaker')) : 0;

        $excavator = new Excavator;
        $excavator->name                    = $name;
        $excavator->description             = $description;
        $excavator->contractor_account_id   = $contractorAccountId;
        $excavator->rent_type               = $rentalType;
        $excavator->rent_monthly            = $rentMonthly;
        $excavator->rent_hourly_bucket      = $rentHourlyBucket;
        $excavator->rent_hourly_breaker     = $rentHourlyBreaker;
        $excavator->status                  = 1;
        if($excavator->save()) {
            return redirect()->back()->with("message","Successfully Saved.")->with("alert-class","alert-success");
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the excavator details. Try again after reloading the page!<small class='pull-right'> #09/01</small>")->with("alert-class","alert-danger");
        }
    }

    /**
     * Return view for excavators listing
     */
    public function list(Request $request)
    {
        $accountId      = !empty($request->get('account_id')) ? $request->get('account_id') : 0;
        $excavatorId    = !empty($request->get('excavator_id')) ? $request->get('excavator_id') : 0;

        $accounts = Account::where('type', 'personal')->where('status', '1')->get();
        $excavatorsCombobox = Excavator::where('status', '1')->get();

        $query = Excavator::where('status', '1');

        if(!empty($accountId) && $accountId != 0) {
            $selectedAccount = Account::find($accountId);
            if(!empty($selectedAccount) && !empty($selectedAccount->id)) {
                $query = $query->where('contractor_account_id', $accountId);
            } else {
                $accountId = 0;
            }
        }

        if(!empty($excavatorId) && $excavatorId != 0) {
            $selectedExcavator = Account::find($excavatorId);
            if(!empty($selectedExcavator) && !empty($selectedExcavator->id)) {
                $query = $query->where('id', $excavatorId);
            } else {
                $excavatorId = 0;
            }
        }

        $excavators = $query->with('account.accountDetail')->orderBy('created_at','desc')->paginate(15);

        return view('excavator.list',[
                'accounts'              => $accounts,
                'excavatorsCombobox'    => $excavatorsCombobox,
                'excavators'            => $excavators,
                'accountId'             => $accountId,
                'excavatorId'           => $excavatorId
            ]);
    }

    /**
     * Return view for account editing
     */
    public function edit(Request $request)
    {
        $excavatorId = !empty($request->get('excavator_id')) ? $request->get('excavator_id') : 0;

        if(!empty($excavatorId) && $excavatorId != 0) {
            $excavator = Excavator::where('id', $excavatorId)->with('account')->first();

            if(empty($excavator) || empty($excavator->id)) {
                return redirect(route('excavator-list'))->with("message","Something went wrong! Selected record not found. Try again after reloading the page!<small class='pull-right'> #09/02</small>")->with("alert-class","alert-danger");
            }
        } else {
            return redirect(route('excavator-list'))->with("message","Something went wrong! Selected record not found. Try again after reloading the page!<small class='pull-right'> #09/03</small>")->with("alert-class","alert-danger");
        }

        return view('excavator.edit',[
                'excavator' => $excavator
            ]);
    }

    /**
     * Handle account updation
     */
    public function updationAction(EmployeeUpdationRequest $request)
    {
        $destination        = '/images/employee/'; // image file upload path
        $flag = 0;
        $employeeId         = !empty($request->get('employee_id')) ? $request->get('employee_id') : 0;
        $name               = $request->get('name');
        $phone              = $request->get('phone');
        $address            = $request->get('address');
        $employeeType       = $request->get('employee_type');
        $salary             = $request->get('salary');
        $wage               = $request->get('wage');

        if ($request->hasFile('image_file')) {
            $file               = $request->file('image_file');
            $extension          = $file->getClientOriginalExtension(); // getting image extension
            $fileName           = $name.'_'.time().'.'.$extension; // renameing image
            $file->move(public_path().$destination, $fileName); // uploading file to given path
        }

        if(!empty($fileName)) {
            $image   = $destination.$fileName;
        } else {
            $image   = $destination."default_employee.jpg";
        }

        $employee   = Employee::find($employeeId);
        $accountId  = $employee->account_id;

        $employee->employee_type    = $employeeType;
        $employee->salary           = $salary;
        $employee->wage             = $wage;
        if($employee->save()) {
            $accountDetail = AccountDetail::where('account_id', $accountId)->first();
            $accountDetail->name        = $name;
            $accountDetail->phone       = $phone;
            $accountDetail->address     = $address;
            $accountDetail->image       = $image;
            if($accountDetail->save()) {
                return redirect(route('employee-list'))->with("message","Successfully updated.")->with("alert-class","alert-success");
            } else{
                return redirect(route('employee-list'))->with("message","Failed to update the account details. Try again after reloading the page!<small class='pull-right'> #08/05</small>")->with("alert-class","alert-danger");
            }
        } else{
            return redirect(route('employee-list'))->with("message","Failed to update the account details. Try again after reloading the page!<small class='pull-right'> #08/06</small>")->with("alert-class","alert-danger");
        }
    }

    /**
     * Return contractor account for excavator id
     */
    public function getAccountByExcavatorId($excavatorId)
    {
        $excavator = Excavator::where('id', $excavatorId)->with('account')->first();
        if(!empty($excavatorId) && !empty($excavator->id)) {
            $accountName = $excavator->account->account_name;
            $accountId   = $excavator->account->id;
            $excavatorRent = ExcavatorRent::where('excavator_id', $excavatorId)->orderBy('to_date','desc')->first();
            if(!empty($excavatorRent) && !empty($excavatorRent->id)) {
                $excavatorLastRentDate = $excavatorRent->to_date;
                $excavatorLastRentDate = new DateTime($excavatorLastRentDate);
                $excavatorLastRentDate->modify('+1 day');
                $excavatorLastRentDate = $excavatorLastRentDate->format('m-d-Y');
            }

            return ([
                    'flag'          => true,
                    'accountName'   => $accountName,
                    'accountId'     => $accountId,
                    'rent'          => ($excavator->rent_type == 'monthly') ? $excavator->rent_monthly : 0,
                    'excavatorLastRentDate' => !empty($excavatorLastRentDate) ? $excavatorLastRentDate : ''
                ]);
        } else {
            return ([
                    'flag' => false
                ]);
        }
    }
}
