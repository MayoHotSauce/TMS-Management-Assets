<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create base permissions
        $permissions = [
            // Level 1 - View Only
            'view_categories',
            'view_rooms',
            'view_assets',
            'view_maintenance',
            'view_stock',
            'view_history',
            'view_asset_requests',
            
            // Level 2 - Create & Edit
            'create_categories',
            'edit_categories',
            'create_rooms',
            'edit_rooms',
            'create_assets',
            'edit_assets',
            'create_maintenance',
            'edit_maintenance',
            'manage_stock',
            'create_asset_requests',
            
            // Level 3 - Delete & Approve
            'delete_categories',
            'delete_rooms',
            'delete_assets',
            'delete_maintenance',
            'approve_maintenance',
            'approve_asset_requests',
            'manage_roles'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles with predefined levels
        $level1 = Role::create(['name' => 'Level 1']);
        $level1->givePermissionTo([
            'view_categories', 'view_rooms', 'view_assets',
            'view_maintenance', 'view_stock', 'view_history',
            'view_asset_requests'
        ]);

        $level2 = Role::create(['name' => 'Level 2']);
        $level2->givePermissionTo([
            // All Level 1 permissions
            'view_categories', 'view_rooms', 'view_assets',
            'view_maintenance', 'view_stock', 'view_history',
            'view_asset_requests',
            // Level 2 permissions
            'create_categories', 'edit_categories',
            'create_rooms', 'edit_rooms',
            'create_assets', 'edit_assets',
            'create_maintenance', 'edit_maintenance',
            'manage_stock', 'create_asset_requests'
        ]);

        $level3 = Role::create(['name' => 'Level 3']);
        $level3->givePermissionTo(Permission::all()); // All permissions
    }
} 