<?php

namespace ClassicO\NovaMediaLibrary\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FolderNewFr extends FormRequest
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
            'base' => 'required|string|regex:/^[a-zA-Z0-9_\-\/]+$/',
            'folder' => 'required|string|regex:/^[a-zA-Z0-9_\-]+$/',
        ];
    }

    public function messages()
    {
        return [
            'base.*' => __('Invalid base path. Use only: a-z 0-9 - _'),
            'folder.*' => __('Invalid new folder name. Use only: a-z 0-9 - _'),
        ];
    }
}
