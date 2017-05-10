<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'name'                  => 'required',
            'phone'                 => 'nullable|unique:accounts',
            'address'               => 'nullable|unique:accounts',
            'account_type'          => 'required',
            'financial_status'      => 'required',
            'opening_balance'       => 'required'
        ];
    }
}
