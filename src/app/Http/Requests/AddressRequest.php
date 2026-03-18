<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address_line1' => ['required', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'postal_code.required' => '郵便番号は必須です',
            'postal_code.regex' => '郵便番号はハイフンあり8文字で入力してください',
            'address_line1.required' => '住所は必須です',
            'address_line1.max' => '住所は255文字以内で入力してください',
            'address_line2.max' => '建物名は255文字以内で入力してください',
        ];
    }
}
