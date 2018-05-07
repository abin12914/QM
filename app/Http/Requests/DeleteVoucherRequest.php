<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Voucher;

class DeleteVoucherRequest extends FormRequest
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
            'voucher_id.*'  => 'Something went wrong. Please try again later.',
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
            'voucher_id'    =>  [
                                    'required',
                                    Rule::in(Voucher::pluck('id')->toArray()),
                                ],
            'date'          =>  [
                                    'required',
                                    'date_format:d-m-Y',
                                ],
        ];
    }
}
