<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Account;
use App\Models\Employee;

class EmployeeSalaryRegistrationRequest extends FormRequest
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
            'emp_salary_account_id.required'    => 'The employee account field is required.',
            'emp_salary_account_id.integer'     => 'Something went wrong. Please try again after reloading the page.',
            'emp_salary_account_id.in'          => 'Something went wrong. Please try again after reloading the page.',
            'emp_salary_employee_id.required'   => 'The employee name field is required.',
            'emp_salary_employee_id.integer'    => 'Something went wrong. Please try again after reloading the page.',
            'emp_salary_employee_id.in'         => 'Something went wrong. Please try again after reloading the page.',
            'emp_salary_start_date.required'    => 'Start date field is required',
            'emp_salary_start_date.date_format' => 'Start date field format error. Please try again after reloading the page.',
            'emp_salary_end_date.required'      => 'End date field is required',
            'emp_salary_end_date.date_format'   => 'End date field format error. Please try again after reloading the page.',
            'emp_salary_end_date.after'         => 'End date should be greater than start date.',
            'emp_salary_salary.required'        => 'The salary field is required',
            'emp_salary_salary.numeric'         => 'The salary field should be nuemeric.',
            'emp_salary_salary.min'             => 'The salary field value should be greater than 0.',
            'emp_salary_salary.max'             => 'The salary field value limit exceeded.',
            'emp_salary_description.required'   => 'The description field is required',
            'emp_salary_description.max'        => 'The description should not be greater than 200 characters.',

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
            'emp_salary_account_id'     => [
                                                'required',
                                                'integer',
                                                Rule::in(Account::pluck('id')->toArray())
                                            ],
            'emp_salary_employee_id'    => [
                                                'required',
                                                'integer',
                                                Rule::in(Employee::pluck('id')->toArray())
                                            ],
            'emp_salary_start_date'     => [
                                                'required',
                                                'date_format:d-m-Y'
                                            ],
            'emp_salary_end_date'       => [
                                                'required',
                                                'date_format:d-m-Y',
                                                'after:emp_salary_start_date'
                                            ],
            'emp_salary_salary'         => [
                                                'required',
                                                'numeric',
                                                'min:10',
                                                'max:50000'
                                            ],
            'emp_salary_description'    => [
                                                'required',
                                                'max:200'
                                            ],
        ];
    }
}
