<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Vehicle;
use App\Models\Account;
use App\Models\Product;

class CreditSaleRegistrationRequest extends FormRequest
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
            'vehicle_id.required'           => "The truck number field is required.",
            'vehicle_id.integer'            => "Something went wrong. Please try again after reloading the page.",
            'vehicle_id.in'                 => "Something went wrong. Please try again after reloading the page.",
            'purchaser_account_id.required' => "The purchaser field is required.",
            'purchaser_account_id.integer'  => "Something went wrong. Please try again after reloading the page.",
            'purchaser_account_id.in'       => "Something went wrong. Please try again after reloading the page.",
            'product_id.required'           => "The product field is required.",
            'product_id.integer'            => "Something went wrong. Please try again after reloading the page.",
            'product_id.in'                 => "Something went wrong. Please try again after reloading the page.",
            'measure_type.integer'          => "Something went wrong. Please try again after reloading the page.",
            'measure_type.in'               => "Something went wrong. Please try again after reloading the page.",
            'quantity.required'             => "Required field.",
            'quantity.integer'              => "Invalid data",
            'quantity.max'                  => "Maximum value exceeded",
            'rate.required'                 => "Required field.",
            'rate.numeric'                  => "Invalid data",
            'rate.max'                      => "Maximum value exceeded",
            'bill_amount.required'          => "Required field.",
            'bill_amount.integer'           => "Invalid data",
            'bill_amount.max'               => "Maximum value exceeded",
            'discount.required'             => "Required field.",
            'discount.integer'              => "Invalid data",
            'discount.max'                  => "Maximum value exceeded",
            'deducted_total.required'       => "Required field.",
            'deducted_total.integer'        => "Invalid data",
            'deducted_total.max'            => "Maximum value exceeded",
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
            'vehicle_id'            => [
                                            'required',
                                            'integer',
                                            Rule::in(Vehicle::pluck('id')->toArray()),
                                        ],
            'purchaser_account_id'  => [
                                            'required',
                                            'integer',
                                            Rule::in(Account::pluck('id')->toArray()),
                                        ],
            'date'                  => [
                                            'required',
                                            'date',
                                        ],
            'time'                  => [
                                            'required',
                                        ],
            'product_id'            => [
                                            'required',
                                            'integer',
                                            Rule::in(Product::pluck('id')->toArray()),
                                        ],
            'measure_type'          => [
                                            'required',
                                            'integer',
                                            Rule::in(['1', '2']),
                                        ],
            'quantity'              => [
                                            'required_if:measure_type,1',
                                            'integer',
                                            'max:2000'
                                        ],
            'rate'                  => [
                                            'required_if:measure_type,1',
                                            'numeric',
                                            'max:9999'
                                        ],
            'bill_amount'           => [
                                            'required_if:measure_type,1',
                                            'numeric',
                                            'max:99999'
                                        ],
            'discount'              => [
                                            'required_if:measure_type,1',
                                            'numeric',
                                            'max:9999'
                                        ],
            'deducted_total'        => [
                                            'required_if:measure_type,1',
                                            'numeric',
                                            'max:99999'
                                        ]
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $input = $validator->input;
            if (($input->quantity * $input->rate) == $input->bill_amount) {
                $validator->errors()->add('bill_amount', 'Invalid data!!');
            }
            if ($input->bill_amount - $input->discount == $input->deducted_total) {
                $validator->errors()->add('deducted_total', 'Invalid data!!');
            }
        });
    }
}
