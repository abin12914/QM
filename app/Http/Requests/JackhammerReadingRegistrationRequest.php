<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Jackhammer;

class JackhammerReadingRegistrationRequest extends FormRequest
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
            'jackhammer_date.required'              => 'Date field is required.',
            'jackhammer_date.date_format'           => 'Something went wrong. Please try again after reloading the page.',
            'jackhammer_id.required'                => 'Jackhammer field is required.',
            'jackhammer_id.integer'                 => 'Something went wrong. Please try again after reloading the page.',
            'jackhammer_id.in'                      => 'Something went wrong. Please try again after reloading the page.',
            'jackhammer_depth_per_pit.required'     => 'Depth per pit field is required.',
            'jackhammer_depth_per_pit.numeric'      => 'Depth per pit field value should be numeric.',
            'jackhammer_depth_per_pit.max'          => 'Depth per pit field value limit exceeded.',
            'jackhammer_depth_per_pit.min'          => 'Depth per pit field value must be greater than 0.',
            'jackhammer_no_of_pit.required'         => 'No of pit field is required.',
            'jackhammer_no_of_pit.numeric'          => 'No of pit field value should be numeric.',
            'jackhammer_no_of_pit.max'              => 'No of pit field value limit exceeded.',
            'jackhammer_no_of_pit.min'              => 'No of pit field value must be greater than 5.',
            'jackhammer_total_pit_depth.required'   => 'Total pit depth field is required.',
            'jackhammer_total_pit_depth.numeric'    => 'Something went wrong. Please try again after reloading the page.',
            'jackhammer_total_pit_depth.max'        => 'Something went wrong. Please try again after reloading the page.',
            'jackhammer_total_pit_depth.min'        => 'Something went wrong. Please try again after reloading the page.',
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
            'jackhammer_date'               => [
                                                    'required',
                                                    'date_format:d-m-Y'
                                                ],
            'jackhammer_id'                 => [
                                                    'required',
                                                    'integer',
                                                    Rule::in(Jackhammer::pluck('id')->toArray())
                                                ],
            'jackhammer_depth_per_pit'      => [
                                                    'required',
                                                    'numeric',
                                                    'max:30',
                                                    'min:1'
                                                ],
            'jackhammer_no_of_pit'          => [
                                                    'required',
                                                    'numeric',
                                                    'max:1000',
                                                    'min:5'
                                                ],
            'jackhammer_total_pit_depth'    => [
                                                    'required',
                                                    'numeric',
                                                    'max:10000',
                                                    'min:10'
                                                ],
        ];
    }
}
