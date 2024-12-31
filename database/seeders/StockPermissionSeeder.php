<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class StockPermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for stock management
        $permissions = [
            'view stock',
            'create stock check',
            'update stock',
            'confirm stock',
            'download stock report',
            'view stock history',
            'manage all stock', // Super permission
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo($permissions);

        // Create stock manager role
        $stockManager = Role::firstOrCreate(['name' => 'stock manager']);
        $stockManager->givePermissionTo([
            'view stock',
            'create stock check',
            'update stock',
            'confirm stock',
            'download stock report',
            'view stock history'
        ]);

        // Create stock checker role
        $stockChecker = Role::firstOrCreate(['name' => 'stock checker']);
        $stockChecker->givePermissionTo([
            'view stock',
            'update stock',
            'view stock history'
        ]);
    }
} 