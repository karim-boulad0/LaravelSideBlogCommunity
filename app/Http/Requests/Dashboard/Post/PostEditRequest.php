<?php

namespace App\Http\Requests\Dashboard\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PostEditRequest extends FormRequest
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
            'user_id' => 'nullable|exists,users,id',
            'title' => 'required|string|max:20|min:2',
            'content' => "required|string|max:80|min:2",
            'smallDesc' => 'required|string|max:30|min:2',
            'tags' => 'nullable|',
        ];
    }
}
