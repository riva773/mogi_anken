<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'content' => '商品コメント',
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => ':attributeは必須です。',
            'content.max' => ':attributeは:max文字以内で入力してください。',
        ];
    }
}
