<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // ユーザー名：必須、20文字以内
            'name' => ['required', 'string', 'max:20'],

            // 郵便番号：必須、ハイフンあり8文字（123-4567）
            'postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],

            // 住所：必須
            'address_line1' => ['required', 'string', 'max:255'],

            // 建物名は要件にないので任意のままでOK（課題にあるなら required に）
            'address_line2' => ['nullable', 'string', 'max:255'],

            // プロフィール画像：jpeg or png（必須指定は要件にないので任意）
            'icon' => ['nullable', 'file', 'mimes:jpeg,png', 'max:2048'],
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'ユーザー名は必須です。',
            'name.max' => 'ユーザー名は20文字以内で入力してください。',

            'postal_code.required' => '郵便番号は必須です。',
            'postal_code.regex' => '郵便番号はハイフンありの8文字（例：123-4567）で入力してください。',

            'address_line1.required' => '住所は必須です。',

            'icon.mimes' => '画像はjpegまたはpng形式のみアップロードできます。',
            'icon.max' => '画像サイズは2MB以内にしてください。',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'ユーザー名',
            'postal_code' => '郵便番号',
            'address_line1' => '住所',
            'address_line2' => '建物名・部屋番号',
            'icon' => 'プロフィール画像',
        ];
    }
}