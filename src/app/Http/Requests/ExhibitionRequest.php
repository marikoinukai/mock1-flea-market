<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'image' => ['required', 'file', 'mimes:png,jpeg'],
            'title' => ['required', 'max:255'],
            'description' => ['required', 'max:255'],
            'category_ids' => ['required', 'array', 'min:1'],
            // 'category_ids.*' => ['exists:categories,id'],
            'item_condition_id' => ['required', 'exists:item_conditions,id'],
            'price' => ['required', 'integer', 'min:0'],
            'brand_name' => ['nullable', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'image.required' => '商品画像を選択してください。',
            'image.image' => '商品画像は画像ファイルを選択してください。',
            'image.mimes' => '商品画像はjpegまたはpng形式を選択してください。',
            'image.max' => '商品画像は2MB以内でアップロードしてください。',

            'title.required' => '商品名を入力してください。',
            'title.max' => '商品名は255文字以内で入力してください。',

            'description.required' => '商品説明を入力してください。',
            'description.max' => '商品説明は255文字以内で入力してください。',

            'category_ids.required' => 'カテゴリーを選択してください。',
            'category_ids.min' => 'カテゴリーを1つ以上選択してください。',
            'category_ids.*.exists' => '選択したカテゴリーが不正です。',

            'item_condition_id.required' => '商品の状態を選択してください。',
            'item_condition_id.exists' => '選択した商品の状態が不正です。',

            'price.required' => '販売価格を入力してください。',
            'price.integer' => '販売価格は整数で入力してください。',
            'price.min' => '販売価格は0円以上で入力してください。',

            'brand_name.max' => 'ブランド名は255文字以内で入力してください。',
        ];
    }
}
