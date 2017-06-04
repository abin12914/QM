<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Vehicle;
use App\Models\Account;
use App\Models\Product;

class CashSaleRegistrationRequest extends FormRequest
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
            'vehicle_id_cash.required'              => "The truck number field is required.",
            'vehicle_id_cash.integer'               => "Something went wrong. Please try again after reloading the page.",
            'vehicle_id_cash.in'                    => "Something went wrong. Please try again after reloading the page.",
            'purchaser_account_id_cash.required'    => "The purchaser field is required.",
            'purchaser_account_id_cash.integer'     => "Something went wrong. Please try again after reloading the page.",
            'purchaser_account_id_cash.in'          => "Something went wrong. Please try again after reloading the page.",
            'product_id_cash.required'              => "The product field is required.",
            'product_id_cash.integer'               => "Something went wrong. Please try again after reloading the page.",
            'product_id_cash.in'                    => "Something went wrong. Please try again after reloading the page.",
            'measure_type_cash.integer'             => "Something went wrong. Please try again after reloading the page.",
            'measure_type_cash.in'                  => "Something went wrong. Please try again after reloading the page.",
            'quantity_cash.required'                => "Required field.",
            'quantity_cash.integer'                 => "Invalid data",
            'quantity_cash.max'                     => "Maximum value exceeded",
            'rate_cash.required'                    => "Required field.",
            'rate_cash.integer'                     => "Invalid data",
            'rate_cash.max'                         => "Maximum value exceeded",
            'bill_amount_cash.required'             => "Required field.",
            'bill_amount_cash.integer'              => "Invalid data",
            'bill_amount_cash.max'                  => "Maximum value exceeded",
            'discount_cash.required'                => "Required field.",
            'discount_cash.integer'                 => "Invalid data",
            'discount_cash.max'                     => "Maximum value exceeded",
            'deducted_total_cash.required'          => "Required field.",
            'deducted_total_cash.integer'           => "Invalid data",
            'deducted_total_cash.max'               => "Maximum value exceeded",
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
            'vehicle_id_cash'           => [
                                                'required',
                                                'integer',
                                                Rule::in(Vehicle::pluck('id')->toArray()),
                                            ],
            'purchaser_account_id_cash' => [
                                                'required',
                                                'integer',
                                                Rule::in(Account::pluck('id')->toArray()),
                                            ],
            'date_cash'                 => [
                                                'required',
                                                'date_format:d/m/Y',
                                            ],
            'time_cash'                 => [
                                                'required',
                                            ],
            'product_id_cash'           => [
                                                'required',
                                                'integer',
                                                Rule::in(Product::pluck('id')->toArray()),
                                            ],
            'measure_type_cash'         => [
                                                'required',
                                                'integer',
                                                Rule::in(['1', '2']),
                                            ],
            'quantity_cash'             => [
                                                'required',
                                                'integer',
                                                'max:2000'
                                            ],
            'rate_cash'                 => [
                                                'required',
                                                'numeric',
                                                'max:9999'
                                            ],
            'bill_amount_cash'          => [    
                                                'required',
                                                'numeric',
                                                'max:99999'
                                            ],
            'discount_cash'             => [
                                                'required',
                                                'numeric',
                                                'max:9999'
                                            ],
            'deducted_total_cash'       => [
                                                'required',
                                                'numeric',
                                                'max:99999'
                                            ],
            'old_balance'               => [
                                                'required',
                                                'numeric',
                                                'max:99999'
                                            ],
            'total'                     => [
                                                'required',
                                                'numeric',
                                                'max:99999'
                                            ],
            'paid_amount'               => [
                                                'required',
                                                'numeric',
                                                'max:99999'
                                            ],
            'balance'                   => [
                                                'required',
                                                'numeric',
                                                'max:99999'
                                            ],
        ];
    }
}
