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
            'account_name.unique'       => 'The account name has already been taken by an existing account. Please verify your entry or use initials.',
            'phone.unique'              => 'The phone number has already been taken by an existing account. Please verify your entry or check for duplicates.',
            'account_type.max'          => 'Something went wrong. Please try again after reloading the page',
            'financial_status.max'      => 'Something went wrong. Please try again after reloading the page',
            'name.required'             => 'The name field is required for personal accounts.',
            'relation_type.required'    => 'The relation type is required for personal accounts.',
            'phone.required'            => 'The phone is required for personal accounts.',
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
            'account_type'          => [
                                            'required',
                                            'max:8',
                                            Rule::in([/*'real','nominal',*/'personal'])
                                        ],
            'financial_status'      => [
                                            'required',
                                            'max:8',
                                            Rule::in(['none','credit','debit'])
                                        ],
            'opening_balance'       => 'required|numeric|max:9999999',
            'name'                  => 'required|max:200',
            'phone'                 => 'required|numeric|digits_between:10,13|unique:account_details',
            'address'               => 'nullable|max:200',
            'relation_type'         => [
                                            'required',
                                            'max:10',
                                            Rule::in(['supplier','customer','contractor','general'])
                                        ],
        ];
    }
}
