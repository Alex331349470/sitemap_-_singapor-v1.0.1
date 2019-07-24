<?php

namespace App\Http\Requests\Api;


class SearchRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'search' => 'string'
        ];
    }
}
