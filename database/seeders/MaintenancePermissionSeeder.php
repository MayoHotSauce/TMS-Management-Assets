<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MaintenancePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for maintenance management
        $permissions = [
            'view maintenances',
            'create maintenance',
            'edit maintenance',
            'delete maintenance',
            'approve maintenance',
            'revise maintenance',
            'complete maintenance',
            'restart maintenance',
            'view maintenance history',
            'manage all maintenances', // Super permission
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo($permissions);

        // Create maintenance manager role
        $maintenanceManager = Role::firstOrCreate(['name' => 'maintenance manager']);
        $maintenanceManager->givePermissionTo([
            'view maintenances',
            'approve maintenance',
            'revise maintenance',
            'view maintenance history'
        ]);

        // Create maintenance technician role
        $maintenanceTechnician = Role::firstOrCreate(['name' => 'maintenance technician']);
        $maintenanceTechnician->givePermissionTo([
            'view maintenances',
            'create maintenance',
            'edit maintenance',
            'complete maintenance',
            'view maintenance history'
        ]);

        // Create maintenance viewer role
        $maintenanceViewer = Role::firstOrCreate(['name' => 'maintenance viewer']);
        $maintenanceViewer->givePermissionTo([
            'view maintenances',
            'view maintenance history'
        ]);
    }
} 