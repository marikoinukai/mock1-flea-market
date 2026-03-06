<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:8'],
            'password_confirmation' => ['required', 'string', 'min:8', 'same:password'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'ユーザー名は必須です。',
            'name.max' => 'ユーザー名は20文字以内で入力してください。',
            'email.required' => 'メールアドレスは必須です。',
            'email.email' => 'メールアドレスの形式が正しくありません。',
            'password.required' => 'パスワードは必須です。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
            'password_confirmation.required' => '確認用パスワードは必須です。',
            'password_confirmation.min' => '確認用パスワードは8文字以上で入力してください。',
            'password_confirmation.same' => '確認用パスワードが一致しません。',
        ];
    }
}
