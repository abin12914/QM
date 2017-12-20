<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Vehicle;
use App\Models\Account;
use App\Models\Product;

class MultipleCreditSaleRegistrationRequest extends FormRequest
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
            'quantity'              => [
                                            'required',
                                            'integer',
                                            'max:30',
                                            'min:1'
                                        ],
            'rate'                  => [
                                            'required',
                                            'numeric',
                                            'max:19999',
                                            'min:100'
                                        ],
            'bill_amount'           => [
                                            'required',
                                            'numeric',
                                            'max:199999',
                                            'min:100'
                                        ],
        ];
    }
}
