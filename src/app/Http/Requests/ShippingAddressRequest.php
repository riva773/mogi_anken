<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShippingAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $postal = $this->input('postal_code');
        if ($postal !== null) {
            $postal = trim($postal);
            $postal = mb_convert_kana($postal, 'n', 'UTF-8');
            $postal = str_replace(
                ['‐', '‑', '–', '—', '−'],
                '-',
                $postal
            );
            $this->merge(['postal_code' => $postal]);
        }
    }

    public function rules(): array
    {
        return [
            'postal_code' => ['required', 'regex:/^\d{3}-?\d{4}$/'],
            'address'     => ['required'],
            'building'    => ['nullable'],
        ];
    }

    public function attributes(): array
    {
        return [
            'postal_code' => '郵便番号',
            'address'     => '住所',
            'building'    => '建物名',
        ];
    }

    public function messages(): array
    {
        return [
            'postal_code.required' => ':attributeは必須です。',
            'postal_code.regex' => ':attributeは「1234567」または「123-4567」の形式で入力してください。',
            'address.required'     => ':attributeは必須です。',
        ];
    }
}
