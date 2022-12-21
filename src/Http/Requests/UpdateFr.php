<?php

namespace ClassicO\NovaMediaLibrary\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateFr extends FormRequest
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
            'id' => 'required|numeric',
            'title' => 'required|string|max:250',
            'private' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'id.*' => __('Invalid id'),
            'title.*' => __('Invalid title'),
            'private.*' => __('Field private must be boolean'),
        ];
    }
}
