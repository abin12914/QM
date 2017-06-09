<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VehicleTypeRegistrationRequest extends FormRequest
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
            'name.required'         => "The generic name for the vehicle type is required.",
            'name.max'              => "The generic name for the vehicle type may not be greater than 200 characters.",
            'name.unique'           => "The generic name for the vehicle type has already been taken by a vehicle type. Please verify your entry.",
            'royalty.*.required'    => "Required field",
            'royalty.*.numeric'     => "This field must be integer",
            'royalty.*.max'         => "Maximum value exceeded",
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
            'name'              => 'required|max:200|unique:vehicle_types',
            'description'       => 'nullable|max:200',
            'generic_quantity'  => 'required|numeric|max:9999',
            'royalty.*'         => 'required|numeric|max:9999',
        ];
    }
}
