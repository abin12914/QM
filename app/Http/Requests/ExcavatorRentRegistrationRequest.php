<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Excavator;

class ExcavatorRentRegistrationRequest extends FormRequest
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
            'excavator_id.required'             => 'The excavator name field is required.',
            'excavator_id.integer'              => 'Something went wrong. Please try again after reloading the page.',
            'excavator_id.in'                   => 'Something went wrong. Please try again after reloading the page.',
            'excavator_from_date.required'      => 'From date field is required',
            'excavator_from_date.date_format'   => 'From date field format error. Please try again after reloading the page.',
            'excavator_to_date.required'        => 'To date field is required',
            'excavator_to_date.date_format'     => 'To date field format error. Please try again after reloading the page.',
            'excavator_to_date.after'           => 'To date should be greater than start date.',
            'excavator_rent.required'           => 'The rent field is required',
            'excavator_rent.numeric'            => 'The rent field should be nuemeric.',
            'excavator_rent.min'                => 'The rent field value should be greater than minimum value.',
            'excavator_rent.max'                => 'The rent field value limit exceeded.',
            'excavator_description.required'    => 'The description field is required',
            'excavator_description.max'         => 'The description should not be greater than 200 characters.',

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
            'excavator_id'          => [
                                            'required',
                                            'integer',
                                            Rule::in(Excavator::pluck('id')->toArray())
                                        ],
            'excavator_from_date'   => [
                                            'required',
                                            'date_format:d-m-Y'
                                        ],
            'excavator_to_date'     => [
                                            'required',
                                            'date_format:d-m-Y',
                                            'after:excavator_from_date'
                                        ],
            'excavator_rent'        => [
                                            'required',
                                            'numeric',
                                            'min:10',
                                            'max:150000'
                                        ],
            'excavator_description' => [
                                            'required',
                                            'max:200'
                                        ],
        ];
    }
}
