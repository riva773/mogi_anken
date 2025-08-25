<?php

return [
    'required' => ':attributeは必須です。',
    'email' => ':attributeの形式が正しくありません。',
    'min' => [
        'string' => ':attributeは:min文字以上で入力してください',
    ],
    'confirmed' => ':attributeと一致しません',
    'attributes' => [
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'name' => 'お名前',
    ],
    'custom' => [
        'email' => [
            'required' => 'メールアドレスを入力してください',
        ],
        'password' => [
            'required' => 'パスワードを入力してください',
            'min' => 'パスワードは8文字以上で入力してください',
            'confirmed' => 'パスワードと一致しません',
        ],
        'name' => [
            'required' => 'お名前を入力してください',
        ],
    ],
];
