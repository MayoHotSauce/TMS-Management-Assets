<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoomPermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for rooms management
        $permissions = [
            'view rooms',
            'create room',
            'edit room',
            'delete room',
            'manage rooms', // Super permission untuk mengelola semua
            'view room assets', // Permission untuk melihat asset di ruangan
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo($permissions);

        // Create specific roles for room management
        $roomManager = Role::firstOrCreate(['name' => 'room manager']);
        $roomManager->givePermissionTo([
            'view rooms',
            'create room',
            'edit room',
            'delete room',
            'view room assets'
        ]);

        $roomViewer = Role::firstOrCreate(['name' => 'room viewer']);
        $roomViewer->givePermissionTo([
            'view rooms',
            'view room assets'
        ]);
    }
} 