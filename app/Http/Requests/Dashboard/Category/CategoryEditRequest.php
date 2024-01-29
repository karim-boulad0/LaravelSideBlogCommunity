<?php

namespace App\Http\Requests\Dashboard\Category;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CategoryEditRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

    public function rules(): array
    {
        return [
            'image' => 'nullable|image|max:3048',
            'title' => 'required|string|max:20|min:2',
            'content' => 'required|string|max:70|min:2',
        ];
    }
}
