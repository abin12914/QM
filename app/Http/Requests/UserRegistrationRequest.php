<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRegistrationRequest extends FormRequest
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
            'role.required'             => 'The user role is required.',
            'valid_till.date_format'    => 'The user validity field should be a date and dd/mm/yyyy formated. (eg:31/12/2000)',
            'image_file.mimes'          => 'The image file should be of type jpeg, jpg, png, or bmp',
            'image_file.size'           => 'The image file size should be less than 3 MB',
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
            'name'                  => 'required|max:200',
            'user_name'             => 'required|unique:users|max:145',
            'email'                 => 'nullable|email|unique:users|max:145',
            'phone'                 => 'required|numeric|digits_between:10,13|unique:users',
            'role'                  => [
                                            'required',
                                            Rule::in(['superadmin','admin','user'])
                                        ],
            'valid_till'            => 'nullable|date_format:d/m/Y',
            'password'              => 'required|min:6|max:25|confirmed',
            'image_file'            => 'nullable|mimes:jpeg,jpg,bmp,png|max:3000'
        ];
    }
}
