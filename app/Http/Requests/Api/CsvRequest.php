<?php

namespace App\Http\Requests\Api;


class CsvRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'csv' => 'required|file'
        ];
    }
}
