<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionManagementSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for role management
        $permissions = [
            'view roles',           // Untuk melihat daftar role
            'create role',          // Untuk membuat role baru
            'edit role',            // Untuk mengedit role
            'delete role',          // Untuk menghapus role
            'assign role',          // Untuk assign role ke user
            'revoke role',          // Untuk mencabut role dari user
            'view permissions',     // Untuk melihat daftar permission
            'grant permission',     // Untuk memberikan permission ke role
            'revoke permission',    // Untuk mencabut permission dari role
            'manage roles',         // Super permission untuk manajemen role
            'manage permissions',   // Super permission untuk manajemen permission
            'manage users',         // Untuk manajemen user dan rolenya
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo($permissions);

        // Assign permissions to manager role (jika ada)
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $managerRole->givePermissionTo([
            'view roles',
            'view permissions',
            'assign role',
            'revoke role'
        ]);
    }
} 