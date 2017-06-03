<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRegistrationRequest extends FormRequest
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
            'vehicle_id' => 'required',
            'purchaser_account_id' => 'required',
            'date' => 'required',
            'time' => 'required',
            'product_id' => 'required',
            'measure_type' => 'required',
            'quantity' => 'required',
            'rate' => 'required',
            'bill_amount' => 'required',
            'discount' => 'required',
            'deducted_total' => 'required',
        ];
    }
}
