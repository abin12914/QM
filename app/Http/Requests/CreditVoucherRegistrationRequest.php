<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Account;

class CreditVoucherRegistrationRequest extends FormRequest
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
            'cash_voucher_date.required'            => "The date field is required.",
            'cash_voucher_date.date_format'         => "Something went wrong. Please try again after reloading the page.",
            'cash_voucher_time.required'            => "The time field is required.",
            'cash_voucher_time.max'                 => "Something went wrong. Please try again after reloading the page.",
            'cash_voucher_account_id.required'      => "The account field is required.",
            'cash_voucher_account_id.integer'       => "Something went wrong. Please try again after reloading the page.",
            'cash_voucher_account_id.in'            => "Something went wrong. Please try again after reloading the page.",
            'cash_voucher_type.required'            => "Transaction type is required.",
            'cash_voucher_type.integer'             => "Maximum value exceeded.",
            'cash_voucher_type.in'                  => "Minimum value expected.",
            'cash_voucher_amount.required'          => "The amount field is required.",
            'cash_voucher_amount.numeric'           => "Invalid data.",
            'cash_voucher_amount.max'               => "Maximum value exceeded.",
            'cash_voucher_amount.min'               => "Minimum value expected.",
            'cash_voucher_description.required'     => "The description field is required.",
            'cash_voucher_description.max'          => "The description may not be greater than 150 characters.",
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
            'cash_voucher_date'         => [
                                                'required',
                                                'date_format:d-m-Y',
                                            ],
            'cash_voucher_time'         => [
                                                'required',
                                                'max:5'
                                            ],
            'cash_voucher_account_id'   => [
                                                'required',
                                                'integer',
                                                Rule::in(Account::pluck('id')->toArray()),
                                            ],
            'cash_voucher_type'         => [
                                                'required',
                                                'integer',
                                                Rule::in(['1','2']),
                                            ],
            'cash_voucher_amount'       => [
                                                'required',
                                                'numeric',
                                                'max:9999999',
                                                'min:0'
                                            ],
            'cash_voucher_description'  => [
                                                'required',
                                                'max:150'
                                            ],
        ];
    }
}
