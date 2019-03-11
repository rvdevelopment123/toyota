<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ClientRequest extends Request
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
            'first_name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            
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
            'name.required' => 'Name is required',
            'company_name.required' => 'Company Name is required',
            'phone.required' => 'Phone is required',
            'address.required' => 'Address is required',
        ];
    }
}
