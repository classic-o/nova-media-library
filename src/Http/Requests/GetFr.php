<?php

namespace ClassicO\NovaMediaLibrary\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetFr extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()->first(),
        ], 422));
    }

    public function rules()
    {
        return [
            'title' => 'nullable|string',
            'from' => 'nullable|date_format:Y-m-d',
            'to' => 'nullable|date_format:Y-m-d',
            'page' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'title.*' => __('Invalid title'),
            'from.*' => __('Invalid FROM date format'),
            'to.*' => __('Invalid TO date format'),
            'page.*' => __('Invalid page'),
        ];
    }
}
