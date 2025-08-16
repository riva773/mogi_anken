<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'first_user@gmail.com'],
            [
                'name' => 'test user',
                'email' => 'first_user@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('FirstUser01'),
                'profile_completed' => true
            ]
        );
    }
}
