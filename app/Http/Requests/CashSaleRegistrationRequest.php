<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'vehicle_id_cash' => 'required',
            'purchaser_account_id_cash' => 'required',
            'date_cash' => 'required',
            'time_cash' => 'required',
            'product_id_cash' => 'required',
            'measure_type_cash' => 'required',
            'quantity_cash' => 'required',
            'rate_cash' => 'required',
            'bill_amount_cash' => 'required',
            'discount_cash' => 'required',
            'deducted_total_cash' => 'required',
        ];
    }
}
