<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Sale;

class DeleteSaleRequest extends FormRequest
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
            'sale_id.*' => 'Something went wrong. Please try again later.',
            'date.*'    => 'Something went wrong. Please try again later.',
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
            'sale_id'   =>  [
                                'required',
                                Rule::in(Sale::pluck('id')->toArray()),
                            ],
            'date'      =>  [
                                'required',
                                'date_format:d-m-Y',
                            ],
        ];
    }
}
