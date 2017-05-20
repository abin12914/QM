<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JackhammerRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
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
            'rent_type'             => [
                                            'required',
                                            'max:7',
                                            Rule::in(['per_day','per_feet'])
                                        ],
            'rate_daily'            => 'required|numeric',
            'rate_feet'             => 'required|numeric',
        ];
    }
}
