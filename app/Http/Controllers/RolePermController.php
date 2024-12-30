<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Jabatan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermController extends Controller
{
    public function index()
    {
        $jabatans = Jabatan::all();
        $users = User::with('member')->get();
        $permissions = Permission::all();
        
        return view('roleperm.index', compact('jabatans', 'users', 'permissions'));
    }

    public function getUsersByJabatan($jabatanId)
    {
        $users = User::with('member')
            ->where('jabatan_id', $jabatanId)
            ->get();

        return response()->json(['users' => $users]);
    }

    public function assign(Request $request)
    {
        $request->validate([
            'permission_type' => 'required|in:jabatan,specific',
            'access_level' => 'required|in:1,2,3'
        ]);

        \DB::beginTransaction();
        try {
            $level = "Level " . $request->access_level;
            $roleTemplate = Role::where('name', $level)->firstOrFail();
            
            if ($request->permission_type === 'jabatan') {
                // For jabatan-wide permissions
                $jabatan = Jabatan::findOrFail($request->jabatan_id);
                $roleName = 'jabatan_' . $jabatan->id_jabatan;
                
                // Create or update the jabatan role
                $jabatanRole = Role::firstOrCreate(['name' => $roleName]);
                $jabatanRole->syncPermissions($roleTemplate->permissions);
                
                // Update all users with this jabatan
                User::where('jabatan_id', $jabatan->id_jabatan)
                    ->get()
                    ->each(function ($user) use ($jabatanRole) {
                        $user->syncRoles([$jabatanRole]);
                    });
                    
            } else {
                // For specific user permissions
                $user = User::findOrFail($request->user_id);
                $user->syncRoles([$roleTemplate]);
            }
            
            \DB::commit();
            return redirect()->back()->with('success', 'Level akses berhasil diperbarui');
            
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function assignRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id'
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            $role = Role::findOrFail($request->role_id);
            
            $user->assignRole($role);
            
            return redirect()->back()->with('success', 'Role berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function assignPermission(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id'
        ]);

        try {
            $role = Role::findOrFail($request->role_id);
            $permission = Permission::findOrFail($request->permission_id);
            
            $role->givePermissionTo($permission);
            
            return redirect()->back()->with('success', 'Permission berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
} 