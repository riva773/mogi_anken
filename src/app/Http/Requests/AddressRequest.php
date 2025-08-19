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
            'name' => ['required'],
            'postal_code' => ['required', 'regex:/^\d{3}-?\d{4}$/'],
            'address' => ['required'],
            'building' => [],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'ユーザー名',
            'postal_code' => '郵便番号',
            'address' => '住所',
            'building' => '建物名',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => ':attributeは必須です。',
            'postal_code.required' => ':attributeは必須です。',
            'postal_code.regex' => ':attributeは「1234567」または「123-4567」の形式で入力してください。',
            'address.required' => ':attributeは必須です。',
        ];
    }
}
