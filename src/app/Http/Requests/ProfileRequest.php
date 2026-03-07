<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->user();

        return [
            'icon' => [
                $user && !$user->icon_path ? 'required' : 'nullable',
                'file',
                'mimes:jpeg,png'
            ],

            'name' => ['required', 'string', 'max:20'],
            'postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address_line1' => ['required', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'icon.required' => 'プロフィール画像を選択してください。',
            'icon.mimes' => 'プロフィール画像はjpegまたはpng形式でアップロードしてください。',

            'name.required' => 'ユーザー名を入力してください。',
            'name.max' => 'ユーザー名は20文字以内で入力してください。',

            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.regex' => '郵便番号はハイフンあり8文字で入力してください。',

            'address_line1.required' => '住所を入力してください。',
            'address_line1.max' => '住所は255文字以内で入力してください。',

            'address_line2.max' => '建物名は255文字以内で入力してください。',
        ];
    }
}
