<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Account;
use App\Models\Employee;

class EmployeeAttendanceRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Customized error messages
     *
     */
    public function messages()
    {
        return [
            'attendance_date.required'          => 'Date field is required',
            'attendance_date.date_format'       => 'Date field format error. Please try again after reloading the page.',
            'attendance_account_id.required'    => 'The account name field is required.',
            'attendance_account_id.integer'     => 'Something went wrong. Please try again after reloading the page.',
            'attendance_account_id.in'          => 'Something went wrong. Please try again after reloading the page.',
            'attendance_employee_id.required'   => 'The employee name field is required.',
            'attendance_employee_id.integer'    => 'Something went wrong. Please try again after reloading the page.',
            'attendance_employee_id.in'         => 'Something went wrong. Please try again after reloading the page.',
            'attendance_wage.required'          => 'Wage field is required',
            'attendance_wage.numeric'           => 'Wage field should be nuemeric.',
            'attendance_wage.min'               => 'Wage field value should be greater than 0.',
            'attendance_wage.max'               => 'Wage field value limit exceeded.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'attendance_date'           => [
                                                'required',
                                                'date_format:d-m-Y'
                                            ],
            'attendance_account_id'     => [
                                                'required',
                                                'integer',
                                                Rule::in(Account::pluck('id')->toArray())
                                            ],
            'attendance_employee_id'    => [
                                                'required',
                                                'integer',
                                                Rule::in(Employee::pluck('id')->toArray())
                                            ],
            'attendance_wage'           => [
                                                'required',
                                                'numeric',
                                                'min:1',
                                                'max:5000'
                                            ],
        ];
    }
}
