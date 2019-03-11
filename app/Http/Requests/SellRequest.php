<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SellRequest extends Request
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
            'customer_id' => 'integer',
            'product_id1' => 'integer',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'customer_id.integer' => 'Please Select A Customer',
            'product_id1.integer' => 'Please Select A Product',
        ];
    }
}
