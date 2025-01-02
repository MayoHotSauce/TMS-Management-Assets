<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UpdateOwnerPermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Cari role owner yang sudah ada
        $owner = Role::where('name', 'owner')->first();

        if ($owner) {
            // Berikan semua permission yang ada ke owner
            $owner->syncPermissions(Permission::all());
            $this->command->info('Successfully updated owner permissions');
        } else {
            $this->command->error('Owner role not found!');
        }
    }
} 