<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $allowed = [
            'ファッション',
            '家電',
            'インテリア',
            'レディース',
            'メンズ',
            'コスメ',
            '本',
            'ゲーム',
            'スポーツ',
            'キッチン',
            'ハンドメイド',
            'アクセサリー',
            'おもちゃ',
            'ベビー・キッズ',
        ];
        return [
            'name' => ['required'],
            'description' => ['required', 'max:255'],
            'image' => ['required', 'mimes:jpeg,png'],
            'categories'   => ['nullable', 'array'],
            'categories.*' => ['string', 'in:' . implode(',', $allowed)],
            'condition' => ['required'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => '商品名',
            'description' => '商品説明',
            'image' => '商品画像',
            'category_id' => '商品のカテゴリー',
            'condition' => '商品の状態',
            'price' => '商品価格',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => ':attributeは必須です。',
            'description.required' => ':attributeは必須です。',
            'description.max' => ':attributeは:max文字以内で入力してください。',
            'image.required' => ':attributeは必須です。',
            'image.mimes' => ':attributeはjpegまたはpng形式のファイルを選択してください。',
            'category_id.required' => ':attributeは必須です。',
            'condition.required' => ':attributeは必須です。',
            'price.required' => ':attributeは必須です。',
            'price.numeric' => ':attributeは数値で入力してください。',
            'price.min' => ':attributeは:min以上で入力してください。',
        ];
    }
}
