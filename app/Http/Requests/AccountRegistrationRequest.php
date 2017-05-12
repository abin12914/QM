<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\AccountType;

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
            'account_type.max'      => 'Something went wrong. Please try again after reloading the page',
            'financial_status.max'  => 'Something went wrong. Please try again after reloading the page'
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
            'name'                  => 'required|max:200|unique:accounts',
            'description'           => 'nullable|max:200',
            'account_type'          => [
                                            'required',
                                            'max:50',
                                            Rule::in(AccountType::pluck('value')->toArray())
                                        ],
            'financial_status'      => [
                                            'required',
                                            'max:8',
                                            Rule::in(['none','credit','debit'])
                                        ],
            'opening_balance'       => 'required|numeric'
        ];
    }
}
