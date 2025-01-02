<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignOwnerRole extends Command
{
    protected $signature = 'role:assign-owner {member_id}';
    protected $description = 'Assign owner role to a user by member_id';

    public function handle()
    {
        $memberId = $this->argument('member_id');

        try {
            // Cari user berdasarkan member_id
            $user = User::where('member_id', $memberId)->first();

            if (!$user) {
                $this->error("User dengan member_id {$memberId} tidak ditemukan!");
                return 1;
            }

            // Pastikan role owner ada
            $ownerRole = Role::firstOrCreate(['name' => 'owner']);

            // Berikan semua permission yang ada ke role owner
            $ownerRole->syncPermissions(Permission::all());

            // Assign role owner ke user
            $user->syncRoles(['owner']); // Menggunakan syncRoles untuk menghapus role lain

            $this->info("Role owner berhasil diberikan ke user {$user->member->nama} ({$memberId})");
            return 0;

        } catch (\Exception $e) {
            $this->error("Terjadi kesalahan: " . $e->getMessage());
            return 1;
        }
    }
} 