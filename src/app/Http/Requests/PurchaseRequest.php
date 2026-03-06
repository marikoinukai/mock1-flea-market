<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method' => ['required', 'in:convenience,card'],

            // 「配送先：選択必須」をフォームで持っている場合だけ使う（例）
            // 'shipping_source' => ['required', 'in:profile,custom'],
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method.required' => '支払い方法を選択してください。',
            'payment_method.in' => '支払い方法の値が不正です。',

            // 'shipping_source.required' => '配送先を選択してください。',
            // 'shipping_source.in' => '配送先の値が不正です。',
        ];
    }
}
