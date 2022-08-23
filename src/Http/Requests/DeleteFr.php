<?php

namespace ClassicO\NovaMediaLibrary\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DeleteFr extends FormRequest
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
            'ids' => 'required|array',
        ];
    }

    public function messages()
    {
        return [
            'ids.*' => __('Invalid field of items ids'),
        ];
    }
}
