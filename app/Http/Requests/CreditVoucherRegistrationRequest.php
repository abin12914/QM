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
            'credit_voucher_date.required'                  => "The date field is required.",
            'credit_voucher_date.date_format'               => "Something went wrong. Please try again after reloading the page.",
            'credit_voucher_time.required'                  => "The time field is required.",
            'credit_voucher_time.max'                       => "Something went wrong. Please try again after reloading the page.",
            'credit_voucher_debit_account_id.required'      => "The debit account field is required.",
            'credit_voucher_debit_account_id.integer'       => "Something went wrong. Please try again after reloading the page.",
            'credit_voucher_debit_account_id.in'            => "Something went wrong. Please try again after reloading the page.",
            'credit_voucher_credit_account_id.required'     => "The credit account field is required.",
            'credit_voucher_credit_account_id.integer'      => "Something went wrong. Please try again after reloading the page.",
            'credit_voucher_credit_account_id.in'           => "Something went wrong. Please try again after reloading the page.",
            'credit_voucher_credit_account_id.different'    => "Debit account and credit acount should not be the same.",
            'credit_voucher_type.required'                  => "Transaction type is required.",
            'credit_voucher_type.integer'                   => "Maximum value exceeded.",
            'credit_voucher_type.in'                        => "Minimum value expected.",
            'credit_voucher_amount.required'                => "The amount field is required.",
            'credit_voucher_amount.numeric'                 => "Invalid data.",
            'credit_voucher_amount.max'                     => "Maximum value exceeded.",
            'credit_voucher_amount.min'                     => "Minimum value expected.",
            'credit_voucher_description.required'           => "The description field is required.",
            'credit_voucher_description.max'                => "The description may not be greater than 150 characters.",
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
            'credit_voucher_date'               => [
                                                        'required',
                                                        'date_format:d-m-Y',
                                                    ],
            'credit_voucher_time'               => [
                                                        'required',
                                                        'max:5'
                                                    ],
            'credit_voucher_debit_account_id'   => [
                                                        'required',
                                                        'integer',
                                                        Rule::in(Account::pluck('id')->toArray()),
                                                    ],
            'credit_voucher_credit_account_id'  => [
                                                        'required',
                                                        'integer',
                                                        'different:credit_voucher_debit_account_id',
                                                        Rule::in(Account::pluck('id')->toArray()),
                                                    ],
            'credit_voucher_amount'             => [
                                                        'required',
                                                        'numeric',
                                                        'max:9999999',
                                                        'min:0'
                                                    ],
            'credit_voucher_description'        => [
                                                        'required',
                                                        'max:150'
                                                    ],
        ];
    }
}
