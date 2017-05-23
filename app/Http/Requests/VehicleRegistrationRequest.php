<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\VehicleType;
use App\Models\VehicleRegistrationStateCode;

class VehicleRegistrationRequest extends FormRequest
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
            'vehicle_reg_number.required'               => "The registration number field is required.",
            'vehicle_reg_number.max'                    => "The registration number may not be greater than 13 characters. Use valid format",
            'vehicle_reg_number.unique'                 => "The registration number has already been taken by an existing vehicle registration. Please verify your entry.",
            'vehicle_reg_number.regex'                  => "The registration number format does not matches. eg: 'KL-07 AA-1234' or 'KL-07 1234'",
            'vehicle_reg_number_state_code.required'    => "The state code in the registration number field is required.",
            'vehicle_reg_number_state_code.max'         => "The state code in the registration number may not be greater than 2 characters. Use valid format",
            'vehicle_reg_number_state_code.in'          => "The state code in the registration number is invalid. Use valid code",
            'vehicle_reg_number_region_code.required'   => "The region code in the registration number field is required.",
            'vehicle_reg_number_region_code.max'        => "The region code in the registration number may not be greater than 2 digits. Use valid format",
            'vehicle_reg_number_region_code.min'        => "The region code in the registration number may not be less than 0001. Use valid format",
            'vehicle_reg_number_region_code.digits'     => "The region code in the registration number may not be greater than 2 digits. Use valid format",
            'vehicle_reg_number_region_code.integer'    => "The region code in the registration number should be an integer. Use valid format",
            'vehicle_reg_number_unique_alphabet.max'    => "The alphabetic code in the registration number may not be greater than 2 characters. Use valid format",
            'vehicle_reg_number_unique_digit.required'  => "The unique number in the registration number field is required.",
            'vehicle_reg_number_unique_digit.max'       => "The unique number in the registration number may not be greater than 4 digits. Use valid format",
            'vehicle_reg_number_unique_digit.integer'   => "The unique number in the registration number should be an integer. Use valid format",
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
            'vehicle_reg_number'                    => [
                                                            'required',
                                                            'max:13',
                                                            'regex:(([A-Z]){2}(-)(?:[0-9]){2}( )(((?:[A-Z]){1,2}(-)([0-9]){1,4})|(([0-9]){1,4})))',
                                                            'unique:vehicles,reg_number',
                                                        ],
            'vehicle_reg_number_state_code'         => [
                                                            'required',
                                                            'max:2',
                                                            Rule::in(VehicleRegistrationStateCode::pluck('code')->toArray()),
                                                        ],
            'vehicle_reg_number_region_code'        => [
                                                            'required',
                                                            'max:99',
                                                            'min:1',
                                                            'digits:2',
                                                            'numeric',
                                                        ],
            'vehicle_reg_number_unique_alphabet'    => [
                                                            'nullable',
                                                            'max:2',
                                                        ],
            'vehicle_reg_number_unique_digit'       => [
                                                            'required',
                                                            'max:9999',
                                                            'integer',
                                                        ],
            'description'                   => 'nullable|max:200',
            'vehicle_type'                  => [
                                                    'required',
                                                    'integer',
                                                    Rule::in(VehicleType::pluck('id')->toArray()),
                                                ],
            'owner_name'                    => 'nullable|max:200',
            'volume'                        => 'required|integer|max:9999',
            'body_type'                     => [
                                                    'required',
                                                    'max:7',
                                                    Rule::in(['level','extra-1','extra-2']),
                                                ],
        ];
    }
}