<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = new User();
        $superAdmin->name = "admin";
        $superAdmin->username = "admin";
        $superAdmin->email = "admin@localhost";
        $superAdmin->password = Hash::make("password");
        $superAdmin->save();
    }
}
