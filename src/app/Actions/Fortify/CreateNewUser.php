<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        Validator::make(
            $input,
            [
                'email' => ['required', 'email'],
                'password' => ['required', 'min:8'],
                'password_confirmation' => ['required', 'min:8', 'same:password'],
            ],
            [
                'email.required' => ':attributeは必須です。',
                'email.email'    => ':attributeの形式が正しくありません。',
                'password.required' => ':attributeは必須です。',
                'password.min'      => ':attributeは:min文字以上で入力してください。',
                'password_confirmation.required' => ':attributeは必須です。',
                'password_confirmation.min'      => ':attributeは:min文字以上で入力してください。',
                'password_confirmation.same'     => ':attributeはパスワードと一致させてください。',
            ],
            [
                'email' => 'メールアドレス',
                'password' => 'パスワード',
                'password_confirmation' => '確認用パスワード',
            ]
        )->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
