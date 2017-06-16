<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Account;
use App\Models\PurchasebleProduct;

class PurchaseRegistrationRequest extends FormRequest
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
            'transaction_type.integer'          => "Something went wrong. Please try again after reloading the page.",
            'transaction_type.in'               => "Something went wrong. Please try again after reloading the page.",
            'supplier_account_id.required_if'   => "The supplier field is required.",
            'supplier_account_id.integer'       => "Something went wrong. Please try again after reloading the page.",
            'supplier_account_id.in'            => "Something went wrong. Please try again after reloading the page.",
            'date.date_format'                  => "Something went wrong. Please try again after reloading the page.",
            'time.max'                          => "Something went wrong. Please try again after reloading the page.",
            'product_id.required'               => "The product field is required.",
            'product_id.integer'                => "Something went wrong. Please try again after reloading the page.",
            'product_id.in'                     => "Something went wrong. Please try again after reloading the page.",
            'bill_no.integer'                   => "Bill number should be an integer",
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
            'transaction_type'      => [
                                            'required',
                                            'integer',
                                            Rule::in(['1','2']),
                                        ],
            'supplier_account_id'   => [
                                            'required_if:transaction_type,1',
                                            'integer',
                                            Rule::in(Account::pluck('id')->toArray()),
                                        ],
            'date'                  => [
                                            'required',
                                            'date_format:d-m-Y',
                                            'max:10',
                                        ],
            'time'                  => [
                                            'required',
                                            'max:5'
                                        ],
            'product_id'            => [
                                            'required',
                                            'integer',
                                            Rule::in(PurchasebleProduct::pluck('id')->toArray()),
                                        ],
            'bill_no'               => [
                                            'nullable',
                                            'integer',
                                        ],
            'description'           => [
                                            'nullable',
                                            'max:200',
                                        ],
            'bill_amount'           => [
                                            'required',
                                            'numeric',
                                            'max:99999',
                                            'min:0'
                                        ],
        ];
    }
}
