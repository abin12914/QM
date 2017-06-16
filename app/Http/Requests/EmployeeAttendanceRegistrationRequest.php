<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Account;

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
            'account_id.required'   => 'The account name field is required.',
            'account_id.integer'    => 'Something went wrong. Please try again after reloading the page.',
            'account_id.in'         => 'Something went wrong. Please try again after reloading the page.',
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
            'date'          => [
                                    'required',
                                    'date_format:d-m-Y'
                                ],
            'account_id'    => [
                                    'required',
                                    'integer',
                                    Rule::in(Account::pluck('id')->toArray())
                                ],
            'wage'          => [
                                    'required',
                                    'numeric'
                                ],
        ];
    }
}
