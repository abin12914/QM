<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Sale;

class WeighmentRegistrationRequest extends FormRequest
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
            'sale_id.required'      => "The truck number field is required.",
            'sale_id.integer'       => "Something went wrong. Please try again after reloading the page.",
            'sale_id.in'            => "Something went wrong. Please try again after reloading the page.",
            'quantity.required'     => "Required field.",
            'quantity.integer'      => "Invalid data",
            'quantity.max'          => "Maximum value exceeded",
            'quantity.min'          => "Minimum value expected",
            'rate.required'         => "Required field.",
            'rate.numeric'          => "Invalid data",
            'rate.max'              => "Maximum value exceeded",
            'rate.min'              => "Minimum value expected",
            'bill_amount.required'  => "Required field.",
            'bill_amount.integer'   => "Invalid data",
            'bill_amount.max'       => "Maximum value exceeded",
            'bill_amount.min'       => "Minimum value expected",
            'discount.required'     => "Required field.",
            'discount.integer'      => "Invalid data",
            'discount.max'          => "Maximum value exceeded",
            'discount.min'          => "Minimum value expected",
            'deducted_total.required'   => "Required field.",
            'deducted_total.integer'    => "Invalid data",
            'deducted_total.max'        => "Maximum value exceeded",
            'deducted_total.min'        => "Minimum value expected",
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
            'sale_id'           => [
                                    'required',
                                    'integer',
                                    Rule::in(Sale::pluck('id')->toArray()),
                                ],
            'quantity'          => [
                                    'required',
                                    'integer',
                                    'max:200',
                                    'min:1'
                                ],
            'rate'              => [
                                    'required',
                                    'numeric',
                                    'max:9999',
                                    'min:0'
                                ],
            'bill_amount'       => [
                                    'required',
                                    'numeric',
                                    'max:99999',
                                    'min:1'
                                ],
            'discount'          => [
                                    'required',
                                    'numeric',
                                    'max:9999',
                                    'min:0',
                                ],
            'deducted_total'    => [
                                    'required',
                                    'numeric',
                                    'max:99999',
                                    'min:1'
                                ]
        ];
    }
}
