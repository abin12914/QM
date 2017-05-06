<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'valid_till.date_format'    => 'The user validity field should be a date and dd/mm/yyyy formated',
            'password.confirmed'        => 'The password confirmation does not match.',
            'image_file.mimes'          => 'The image file should be of type jpeg, jpg, png, or bmp',
            /*'image_file.size'           => 'The image file size should be less than 3 MB',*/
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
            'user_name'             => 'required|unique:users',
            'email'                 => 'nullable|email|unique:users',
            'phone'                 => 'required|digits_between:10,13|unique:users',
            'role'                  => 'required',
            'valid_till'            => 'nullable|date_format:d/m/Y',
            'password'              => 'required|min:6|max:10|confirmed',
            'image_file'            => 'nullable|mimes:jpeg,jpg,bmp,png|max:3000'
        ];
    }
}
