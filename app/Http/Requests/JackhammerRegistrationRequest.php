<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Account;

class JackhammerRegistrationRequest extends FormRequest
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
            /*'rate_daily.required'               => "The daily rent field is required. Use zero for empty value",
            'rate_daily.numeric'                => "The daily rent must be a number.",*/
            'rate_feet.required'                => "The rent per feet field is required.",
            'rate_feet.numeric'                 => "The rent per feet must be a number.",
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
            'name'                  => 'required|max:200|unique:jackhammers',
            'description'           => 'nullable|max:200',
            'contractor_account_id' => [
                                            'required',
                                            'integer',
                                            Rule::in(Account::pluck('id')->toArray()),
                                        ],
            /*'rent_type'             => [
                                            'required',
                                            'max:8',
                                            Rule::in(['per_day','per_feet'])
                                        ],
            'rate_daily'            => 'required|numeric|max:99999',*/
            'rate_feet'             => 'required|numeric|max:9999',
        ];
    }
}
