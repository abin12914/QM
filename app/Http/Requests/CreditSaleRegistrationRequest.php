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
            'vehicle_id.required'           => "The truck number is required.",
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
            'quantity.required_if'          => "Required field.",
            'quantity.integer'              => "Invalid data.",
            'quantity.max'                  => "Maximum value exceeded.",
            'quantity.min'                  => "Minimum value expected.",
            'rate.required_if'              => "Required field.",
            'rate.numeric'                  => "Invalid data.",
            'rate.max'                      => "Maximum value exceeded.",
            'rate.min'                      => "Minimum value expected.",
            'bill_amount.required_if'       => "Required field.",
            'bill_amount.integer'           => "Invalid data.",
            'bill_amount.max'               => "Maximum value exceeded.",
            'bill_amount.min'               => "Minimum value expected.",
            'discount.required_if'          => "Required field.",
            'discount.integer'              => "Invalid data.",
            'discount.max'                  => "Maximum value exceeded.",
            'discount.min'                  => "Minimum value expected.",
            'deducted_total.required_if'    => "Required field.",
            'deducted_total.integer'        => "Invalid data.",
            'deducted_total.max'            => "Maximum value exceeded.",
            'deducted_total.min'            => "Minimum value expected.",
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
                                            'date_format:d-m-Y',
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
                                            'max:2000',
                                            'min:0'
                                        ],
            'rate'                  => [
                                            'required_if:measure_type,1',
                                            'numeric',
                                            'max:9999',
                                            'min:0'
                                        ],
            'bill_amount'           => [
                                            'required_if:measure_type,1',
                                            'numeric',
                                            'max:99999',
                                            'min:0'
                                        ],
            'discount'              => [
                                            'required_if:measure_type,1',
                                            'numeric',
                                            'max:9999',
                                            'min:0',
                                        ],
            'deducted_total'        => [
                                            'required_if:measure_type,1',
                                            'numeric',
                                            'max:99999',
                                            'min:0'
                                        ]
        ];
    }
}
