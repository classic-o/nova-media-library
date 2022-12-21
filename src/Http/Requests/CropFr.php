<?php

namespace ClassicO\NovaMediaLibrary\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CropFr extends FormRequest
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
            'x' => 'required|numeric',
            'y' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'rotate' => 'required|numeric|min:0|max:360',
            'over' => 'required|integer|min:0|max:1',
        ];
    }

    public function messages()
    {
        return [
            '*' => __('Invalid request data'),
        ];
    }
}
