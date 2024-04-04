<?php

namespace Database\Factories\User;

use App\Enums\User\UserTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => 'admin@admin.com',
            'login' => 'admin',
            'email_verified_at' => now(),
            'type' => UserTypeEnum::ADMIN,
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }
}
