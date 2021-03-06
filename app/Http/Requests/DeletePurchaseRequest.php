<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Purchase;

class DeletePurchaseRequest extends FormRequest
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

    public function messages()
    {
        return [
            'purchase_id.*' => 'Something went wrong. Please try again later.',
            'date.*'        => 'Something went wrong. Please try again later.',
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
            'purchase_id'   =>  [
                                    'required',
                                    Rule::in(Purchase::pluck('id')->toArray()),
                                ],
            'date'          =>  [
                                    'required',
                                    'date_format:d-m-Y',
                                ],
        ];
    }
}
