<?php

namespace App\Http\Requests\Dashboard\Setting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SettingStoreAndUpdateRequest extends FormRequest
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
            'title' => 'required|string',
            'site_name' => 'required|string',
            'phone_number' => 'required|string',
            'email' => 'required|email',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,ico|max:3048',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,ico|max:3048',
            'facebook' => 'nullable|string',
            'instagram' => 'nullable|string',
            'twitter' => 'nullable|string',
            'linkedin' => 'nullable|string',
        ];
    }
}
