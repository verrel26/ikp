<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $beritaPermission = [
            "read-berita",
            "create-berita",
            "update-berita",
            "delete-berita",
        ];
        if (count($beritaPermission) > 0) {
            foreach ($beritaPermission as $permission) {
                Permission::create([
                    'name' => $permission,
                    'guard_name' => 'web',
                ]);
            }
        }
        $fotoPermission = [
            "read-foto",
            "create-foto",
            "update-foto",
            "delete-foto",
        ];
        if (count($fotoPermission) > 0) {
            foreach ($fotoPermission as $permission) {
                Permission::create([
                    'name' => $permission,
                    'guard_name' => 'web',
                ]);
            }
        }
        $videoPermission = [
            "read-video",
            "create-video",
            "update-video",
            "delete-video",
        ];
        if (count($videoPermission) > 0) {
            foreach ($videoPermission as $permission) {
                Permission::create([
                    'name' => $permission,
                    'guard_name' => 'web',
                ]);
            }
        }
        $userPermission = [
            "read-user",
            "create-user",
            "update-user",
            "delete-user",
        ];
        if (count($userPermission) > 0) {
            foreach ($userPermission as $permission) {
                Permission::create([
                    'name' => $permission,
                    'guard_name' => 'web',
                ]);
            }
        }
        $permissionPermission = [
            "read-permission",
            "create-permission",
            "update-permission",
            "delete-permission",
        ];
        if (count($permissionPermission) > 0) {
            foreach ($permissionPermission as $permission) {
                Permission::create([
                    'name' => $permission,
                    'guard_name' => 'web'
                ]);
            }
        }

        $rolePermission = [
            "read-role",
            "create-role",
            "update-role",
            "delete-role",
            "assing-permission"
        ];
        if (count($rolePermission) > 0) {
            foreach ($rolePermission as $permission) {
                if ($permission !== 'update-role' && $permission !== 'delete-role') {
                    Permission::create([
                        'name' => $permission,
                        'guard_name' => 'web'
                    ]);
                }
            }
        }


        // $adminPermission = [
        //     $beritaPermission,
        //     $fotoPermission,
        //     $videoPermission,
        //     $permissionPermission,
        //     $rolePermission,
        // ];

        // if (count($adminPermission) > 0) {
        //     $adminRole = Role::findByName('admin');
        //     foreach ($adminPermission as $permission) {
        //         $adminRole->givePermissionTo($permission);
        //     }
        // }
    }
}
