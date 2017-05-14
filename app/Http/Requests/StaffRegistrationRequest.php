<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StaffRegistrationRequest extends FormRequest
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
            'image_file.mimes'          => 'The image file should be of type jpeg, jpg, png, or bmp',
            'image_file.size'           => 'The image file size should be less than 3 MB',
            'financial_status.max'      => 'Something went wrong. Please try again after reloading the page',
            'phone.unique'              => 'The phone number has already been taken by an existing account. Please verify your entry.',
            'name.unique'               => 'The name has already been taken by an existing account. Please verify your entry or use initials.',
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
            'name'                  => 'required|max:200|unique:accounts|unique:staff',
            'phone'                 => 'required|numeric|digits_between:10,13|unique:staff|unique:owners',
            'address'               => 'nullable|max:200',
            'image_file'            => 'nullable|mimes:jpeg,jpg,bmp,png|max:3000',
            'salary'                => 'required|numeric',
            'financial_status'      => [
                                            'required',
                                            'max:8',
                                            Rule::in(['none','credit','debit'])
                                        ],
            'opening_balance'       => 'required|numeric'
        ];
    }
}
