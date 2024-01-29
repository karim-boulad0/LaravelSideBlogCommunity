<?php

namespace App\Http\Requests\Dashboard\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PostStoreRequest extends FormRequest
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
            'image' => 'nullable|image',
            'category_id' => 'nullable|exists:categories,id',
            'user_id' => 'nullable|exists:users,id',
            'title' => 'nullable|',
            'content' => 'nullable|',
            'smallDesc' => 'nullable|',
            'tags' => 'nullable|',
        ];
    }
}
