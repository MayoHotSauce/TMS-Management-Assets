<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AssetPermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for asset management
        $permissions = [
            'view assets',
            'create asset',
            'edit asset',
            'delete asset',
            'manage assets', // Super permission
            'print asset label',
            'change asset status',
            'update company logo',
            'view asset history'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo($permissions);

        // Create asset manager role
        $assetManager = Role::firstOrCreate(['name' => 'asset manager']);
        $assetManager->givePermissionTo([
            'view assets',
            'create asset',
            'edit asset',
            'print asset label',
            'change asset status',
            'view asset history'
        ]);

        // Create asset viewer role
        $assetViewer = Role::firstOrCreate(['name' => 'asset viewer']);
        $assetViewer->givePermissionTo([
            'view assets',
            'view asset history'
        ]);
    }
} 