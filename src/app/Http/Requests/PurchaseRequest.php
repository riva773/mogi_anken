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
            'payment_method' => ['required'],
            'shipping' => ['required'],
        ];
    }

    public function attributes(): array
    {
        return [
            'payment_method' => '支払い方法',
            'shipping' => '配送先',
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method.required' => ':attributeは必須です。',
            'shipping.required' => ':attributeは必須です。',
        ];
    }
}
