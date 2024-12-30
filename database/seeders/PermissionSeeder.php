<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Category Permissions
        Permission::create(['name' => 'view_categories']);
        Permission::create(['name' => 'create_categories']);
        Permission::create(['name' => 'edit_categories']);
        Permission::create(['name' => 'delete_categories']);

        // Room Permissions
        Permission::create(['name' => 'view_rooms']);
        Permission::create(['name' => 'create_rooms']);
        Permission::create(['name' => 'edit_rooms']);
        Permission::create(['name' => 'delete_rooms']);

        // Asset List Permissions
        Permission::create(['name' => 'view_assets']);
        Permission::create(['name' => 'create_assets']);
        Permission::create(['name' => 'edit_assets']);
        Permission::create(['name' => 'delete_assets']);

        // Maintenance Permissions
        Permission::create(['name' => 'view_maintenance']);
        Permission::create(['name' => 'create_maintenance']);
        Permission::create(['name' => 'edit_maintenance']);
        Permission::create(['name' => 'delete_maintenance']);
        Permission::create(['name' => 'approve_maintenance']);

        // Asset Request Permissions
        Permission::create(['name' => 'view_asset_requests']);
        Permission::create(['name' => 'create_asset_requests']);
        Permission::create(['name' => 'approve_asset_requests']);
        Permission::create(['name' => 'reject_asset_requests']);

        // History Permissions
        Permission::create(['name' => 'view_history']);

        // Stock Permissions
        Permission::create(['name' => 'view_stock']);
        Permission::create(['name' => 'manage_stock']);

        // Role & Permission Management
        Permission::create(['name' => 'manage_roles']);
        Permission::create(['name' => 'assign_permissions']);
    }
} 