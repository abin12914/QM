<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountUpdationRequest extends FormRequest
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
            'phone.unique'              => 'The phone number has already been taken by an existing account. Please verify your entry or check for duplicates.',
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
            'description'           => 'nullable|max:200',
            'name'                  => 'required|max:200',
            'phone'                 => [
                                            'required',
                                            'numeric',
                                            'digits_between:10,13',
                                            Rule::unique('account_details')->ignore($this->account_id),
                                        ],
            'address'               => 'nullable|max:200',
            'relation_type'         => [
                                            'required',
                                            'max:10',
                                            Rule::in(['supplier','customer','contractor','general'])
                                        ],
        ];
    }
}
