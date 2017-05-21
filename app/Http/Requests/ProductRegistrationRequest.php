<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRegistrationRequest extends FormRequest
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
            'name.required'         => "The product name field is required.",
            'name.max'              => "The product name may not be greater than 200 characters.",
            'name.unique'           => "The product name has already been taken by an existing product. Please verify your entry.",
            'rate_feet.required'    => "The rate per cubic feet field is required.",
            'rate_feet.numeric'     => "The rate per cubic feet field should be a number.",
            'rate_feet.required'    => "The rate per metric ton field is required.",
            'rate_feet.numeric'     => "The rate per metric ton field should be a number.",
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
            'name'          => 'required|max:200|unique:products',
            'description'   => 'nullable|max:200',
            'rate_feet'     => 'required|numeric|max:9999',
            'rate_ton'      => 'required|numeric|max:9999',
        ];
    }
}
