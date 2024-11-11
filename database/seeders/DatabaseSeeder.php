<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'razak',
            'email' => 'razak@localhost',
            'email_verified_at' => now(),
            'password' => Hash::make("password"),
            'remember_token' => Str::random(10)
        ]);
    }
}
