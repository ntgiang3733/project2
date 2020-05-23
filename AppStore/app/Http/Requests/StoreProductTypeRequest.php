<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductTypeRequest extends FormRequest
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
            'name' => 'required|min:2|max:255|unique:producttype,name',

        ];
    }

    public function message() {
        return [
            'required' => ':attribute khong duoc de trong',
            'min' => ':attribute toi thieu 2 ky tu',
            'max' => ':attribute toi da 255 ky tu',
            'unique' => ':attribute da ton tai'
        ];
    }

    public function attribute() {
        return [
            'name' => 'Ten loai san pham'
        ];
    }
}
