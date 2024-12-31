<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PengajuanPermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for pengajuan asset
        $permissions = [
            'view pengajuan',
            'create pengajuan',
            'edit pengajuan',
            'delete pengajuan',
            'approve pengajuan',
            'reject pengajuan',
            'submit proof pengajuan',
            'view proof pengajuan',
            'final approve pengajuan',
            'final reject pengajuan',
            'manage all pengajuan' // Super permission
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo($permissions);

        // Create approver role
        $approverRole = Role::firstOrCreate(['name' => 'pengajuan approver']);
        $approverRole->givePermissionTo([
            'view pengajuan',
            'approve pengajuan',
            'reject pengajuan',
            'view proof pengajuan',
            'final approve pengajuan',
            'final reject pengajuan'
        ]);

        // Create requester role
        $requesterRole = Role::firstOrCreate(['name' => 'pengajuan requester']);
        $requesterRole->givePermissionTo([
            'view pengajuan',
            'create pengajuan',
            'submit proof pengajuan',
            'view proof pengajuan'
        ]);
    }
} 