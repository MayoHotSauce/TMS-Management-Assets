<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AssignAdminRole extends Command
{
    protected $signature = 'role:assign-admin {member_id}';
    protected $description = 'Assign admin role to a user by member_id';

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

            // Pastikan role admin ada
            $adminRole = Role::firstOrCreate(['name' => 'admin']);

            // Assign role admin ke user
            $user->assignRole('admin');

            $this->info("Role admin berhasil diberikan ke user {$user->member->nama} ({$memberId})");
            return 0;

        } catch (\Exception $e) {
            $this->error("Terjadi kesalahan: " . $e->getMessage());
            return 1;
        }
    }
} 