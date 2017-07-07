<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Excavator;

class ExcavatorReadingRegistrationRequest extends FormRequest
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
            'excavator_date.required'           => 'Date field is required',
            'excavator_date.date_format'        => 'Date field format error. Please try again after reloading the page.',
            'excavator_id.required'             => 'Excavator field is required.',
            'excavator_id.integer'              => 'Something went wrong. Please try again after reloading the page.',
            'excavator_id.in'                   => 'Something went wrong. Please try again after reloading the page.',
            'excavator_bucket_hour.required'    => 'Bucket hour field is required.',
            'excavator_bucket_hour.numeric'     => 'Bucket hour field value should be numeric.',
            'excavator_bucket_hour.max'         => 'Bucket hour field value limit exceeded.',
            'excavator_bucket_hour.min'         => 'Bucket hour field value should be greater than 0.',
            'excavator_breaker_hour.required'   => 'Breaker hour field is required.',
            'excavator_breaker_hour.numeric'    => 'Breaker hour field value should be numeric.',
            'excavator_breaker_hour.max'        => 'Breaker hour field value limit exceeded.',
            'excavator_breaker_hour.min'        => 'Breaker hour field value should be greater than 0.',
            'excavator_operator.required'       => 'Operator name field is required.',
            'excavator_operator.max'            => 'Operator name field may not be greater than 50 characters.',
            'excavator_operator.min'            => 'Invalid operator name value.',
            'excavator_operator_bata.required'  => 'Operator bata field is required.',
            'excavator_operator_bata.numeric'   => 'Operator bata field value should be numeric.',
            'excavator_operator_bata.max'       => 'Operator bata field value limit exceeded.',
            'excavator_operator_bata.min'       => 'Operator bata field minimum value required.',
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
            'excavator_date'            => [
                                                'required',
                                                'date_format:d-m-Y'
                                            ],
            'excavator_id'              => [
                                                'required',
                                                'integer',
                                                Rule::in(Excavator::pluck('id')->toArray())
                                            ],
            'excavator_bucket_hour'     => [
                                                'required',
                                                'numeric',
                                                'max:100',
                                                'min:0'
                                            ],
            'excavator_breaker_hour'    => [
                                                'required',
                                                'numeric',
                                                'max:100',
                                                'min:0'
                                            ],
            'excavator_operator'        => [
                                                'required',
                                                'max:50',
                                                'min:2'
                                            ],
            'excavator_operator_bata'   => [
                                                'required',
                                                'numeric',
                                                'max:10000',
                                                'min:10'
                                            ],
        ];
    }
}
