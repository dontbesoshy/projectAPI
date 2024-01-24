<?php

namespace Database\Seeders;

use App\Models\RegisterToken;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create();

        RegisterToken::factory()->create(['user_id' => $user->id]);
    }
}
