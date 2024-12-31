<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CategoryPermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for categories management
        $permissions = [
            'view categories',
            'create category',
            'edit category',
            'delete category',
            'manage categories' // Super permission untuk mengelola semua
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo($permissions);

        // Create specific roles for category management
        $categoryManager = Role::firstOrCreate(['name' => 'category manager']);
        $categoryManager->givePermissionTo([
            'view categories',
            'create category',
            'edit category',
            'delete category'
        ]);

        $categoryViewer = Role::firstOrCreate(['name' => 'category viewer']);
        $categoryViewer->givePermissionTo([
            'view categories'
        ]);
    }
} 