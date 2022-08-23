<?php

namespace ClassicO\NovaMediaLibrary\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FolderDelFr extends FormRequest
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
            'folder' => 'required|string|regex:/^[a-zA-Z0-9_\-\/]+$/',
        ];
    }

    public function messages()
    {
        return [
            'folder.*' => __('Invalid path'),
        ];
    }
}
