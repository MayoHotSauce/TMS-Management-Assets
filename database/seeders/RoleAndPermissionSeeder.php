<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage roles']);
        Permission::create(['name' => 'manage permissions']);
        Permission::create(['name' => 'manage assets']);
        Permission::create(['name' => 'view assets']);
        Permission::create(['name' => 'create assets']);
        Permission::create(['name' => 'edit assets']);
        Permission::create(['name' => 'delete assets']);
        Permission::create(['name' => 'approve requests']);

        // Create roles and assign permissions
        $owner = Role::create(['name' => 'owner']);
        $owner->givePermissionTo(Permission::all()); // Owner has all permissions

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'manage users',
            'manage assets',
            'view assets',
            'create assets',
            'edit assets',
            'delete assets',
            'approve requests'
        ]);

        $karyawan = Role::create(['name' => 'karyawan']);
        $karyawan->givePermissionTo([
            'view assets',
            'create assets'
        ]);
    }
}