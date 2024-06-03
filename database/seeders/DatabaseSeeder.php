<?php

namespace Database\Seeders;

// use Str;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // $this->call(AssignRoleSeeder::class);
        $this->call(SuperAdminUserSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'admin',
        //     'email' => 'test@example.com',
        // ]);

        // $role_superadmin = Role::create(['name' => 'Superadmin']);
        // $role_admin = Role::create(['name' => 'Admin']);

        // $akun_superadmin = new User();
        // $superadmin = new User();
        // $superadmin->fill([
        //     'name' => 'superadmin',
        //     'email' => 'superadmin@gmail.com',
        //     'email_verified_at' => now(),
        //     'password' => Hash::make("password"),
        //     'remember_token' => Str::random(10),
        // ]);
        // $akun_superadmin->save();
        // $akun_superadmin->assignRole($role_superadmin);

        // $akun_admin = new User();
        // $akun_admin->fill([
        //     'name' => 'admin',
        //     'email' => 'admin@gmail.com',
        //     'email_verified_at' => now(),
        //     'password' => Hash::make("password"),
        //     'remember_token' => Str::random(10),
        // ]);
        // $akun_admin->save();
        // $akun_admin->assignRole($role_admin);


    }
}
