<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Account;

class ExcavatorRegistrationRequest extends FormRequest
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
            'contractor_account_id.required'    => "The contractor or provider account field is required.",
            'contractor_account_id.integer'     => "Invalid value passed, Select a valid contractor or provider.",
            'contractor_account_id.in'          => "The selected contractor account is invalid.",
            'rate_monthly.required'             => "The monthly rent field is required.",
            'rate_monthly.numeric'              => "The monthly rent must be a number.",
            'rate_bucket.required'              => "The hourly bucket rent field is required.",
            'rate_bucket.numeric'               => "The hourly bucket rent must be a number.",
            'rate_breaker.required'             => "The hourly breaker rent field is required.",
            'rate_breaker.numeric'              => "The hourly breaker rent must be a number.",
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
            'name'                  => 'required|max:200|unique:excavators',
            'description'           => 'nullable|max:200',
            'contractor_account_id' => [
                                            'required',
                                            'integer',
                                            Rule::in(Account::pluck('id')->toArray()),
                                ],
            'rent_type'     => [
                                    'required',
                                    'max:7',
                                    Rule::in(['hourly','monthly'])
                                ],
            'rate_monthly'  => 'required|numeric',
            'rate_bucket'   => 'required|numeric',
            'rate_breaker'  => 'required|numeric'
        ];
    }
}
