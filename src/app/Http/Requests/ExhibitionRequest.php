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
            'category_ids' => ['required', 'array'],
            'category_ids.*' => ['exists:categories,id'],
            'item_condition_id' => ['required', 'exists:item_conditions,id'],
            'price' => ['required', 'integer', 'min:0'],
            'brand_name' => ['nullable', 'max:255'],
        ];
    }
}
