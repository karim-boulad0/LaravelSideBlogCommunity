<?php

namespace App\Http\Requests\WebSite\User;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserEditRequest extends FormRequest
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
            'name' => 'required',
            'email' =>
            'required',
            'email',
            'string',
            'max:100',
            Rule::unique('users', 'email')->ignore(Auth::id()),
            'password' => 'nullable|string|min:8|max:25',
            'photo' => "nullable",

        ];
    }
}
