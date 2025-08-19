<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $postal = $this->input('postal_code');
        if ($postal !== null) {
            $postal = mb_convert_kana($postal, 'n', 'UTF-8');
            $postal = preg_replace('/[^\d-]/', '', $postal);
            $this->merge(['postal_code' => $postal]);
        }
    }

    public function rules(): array
    {
        return [
            'name'        => ['required'],
            'postal_code' => ['nullable', 'regex:/^\d{3}-?\d{4}$/'],
            'address'     => ['nullable'],
            'building'    => ['nullable'],
            'avatar'      => ['sometimes', 'mimes:jpeg,png'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'        => 'お名前',
            'postal_code' => '郵便番号',
            'address'     => '住所',
            'building'    => '建物名',
            'avatar'      => 'プロフィール画像',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => ':attributeは必須です。',
            'postal_code.regex'  => ':attributeは「1234567」または「123-4567」の形式で入力してください。',
            'avatar.mimes'       => ':attributeはjpegまたはpng形式のファイルを選択してください。',
        ];
    }
}
