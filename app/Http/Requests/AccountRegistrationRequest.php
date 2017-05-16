<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountRegistrationRequest extends FormRequest
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
            'account_name.unique'   => 'The account name has already been taken by an existing account. Please verify your entry or use initials.',
            'phone.unique'          => 'The phone number has already been taken by an existing account. Please verify your entry or check for duplicates.',
            'account_type.max'      => 'Something went wrong. Please try again after reloading the page',
            'financial_status.max'  => 'Something went wrong. Please try again after reloading the page',
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
            'account_name'          => 'required|max:200|unique:accounts',
            'description'           => 'nullable|max:200',
            'account_type'          => 'required|integer|exists:account_types,id',
            'financial_status'      => [
                                            'required',
                                            'max:8',
                                            Rule::in(['none','credit','debit'])
                                        ],
            'opening_balance'       => 'required|numeric',
            'name'                  => 'sometimes|required|max:200',
            'phone'                 => 'sometimes|required|numeric|digits_between:10,13|unique:account_details',
            'address'               => 'nullable|max:200',
        ];
    }
}
