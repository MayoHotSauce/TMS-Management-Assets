<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Jabatan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\Member;

class RolePermController extends Controller
{
    public function index()
    {
        $userRoles = Role::with(['permissions'])
                    ->where(function($query) {
                        $query->where('name', 'like', 'user_%')
                              ->orWhere('name', 'like', '%_level_%');
                    })
                    ->where(function($query) {
                        $query->where('name', 'not like', '%staff%')
                              ->where('name', 'not like', '%jabatan%');
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();

        \Log::info('User Roles:', $userRoles->toArray());
                
        $jabatanRoles = Role::with('permissions')
                        ->where(function($query) {
                            $query->where('name', 'like', 'jabatan_%')
                                  ->orWhere('name', 'like', '%staff%');
                        })
                        ->orderBy('created_at', 'desc')
                        ->get();
                            
        $jabatans = Jabatan::where('id_jabatan', '<=', 28)->get();
        
        $users = User::with(['member', 'jabatan'])
                    ->select('id_user', 'member_id', 'jabatan_id')
                    ->get();

        return view('roleperm.index', compact(
            'userRoles', 
            'jabatanRoles', 
            'jabatans', 
            'users'
        ));
    }

    public function getUsersByJabatan($jabatanId)
    {
        $users = User::with('member')
                    ->where('jabatan_id', $jabatanId)
                    ->select('id_user', 'member_id', 'jabatan_id')
                    ->get();

        return response()->json($users);
    }

    public function getAllUsers()
    {
        $users = DB::table('members')
            ->select('members.*', 'jabatans.nama as jabatan_nama')
            ->leftJoin('jabatans', 'members.jabatan_id', '=', 'jabatans.id_jabatan')
            ->get();

        return response()->json(['users' => $users]);
    }

    public function assign(Request $request)
    {
        try {
            $validated = $request->validate([
                'type' => 'required|in:jabatan,specific',
                'level' => 'required|in:1,2,3',
                'user_id' => 'required_if:type,specific',
                'jabatan_id' => 'required_if:type,jabatan'
            ]);

            if ($request->type === 'specific') {
                $user = User::with('member')->find($request->user_id);
                
                if (!$user) {
                    return response()->json(['status' => 'error', 'message' => 'User tidak ditemukan'], 404);
                }

                // Cek apakah user sudah memiliki role
                $existingRole = Role::where('name', 'like', "user_{$user->id_user}_level_%")->first();
                if ($existingRole) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'User sudah memiliki akses. Silakan edit level akses yang ada.',
                        'existingRole' => $existingRole
                    ], 400);
                }

                $roleName = "user_{$user->id_user}_level_{$request->level}";
            } else {
                $jabatan = Jabatan::find($request->jabatan_id);
                if (!$jabatan) {
                    return response()->json(['status' => 'error', 'message' => 'Jabatan tidak ditemukan'], 404);
                }

                // Cek apakah jabatan sudah memiliki role
                $existingRole = Role::where('name', 'like', "jabatan_{$jabatan->id_jabatan}_level_%")->first();
                if ($existingRole) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Jabatan sudah memiliki akses. Silakan edit level akses yang ada.',
                        'existingRole' => $existingRole
                    ], 400);
                }

                $roleName = "jabatan_{$jabatan->id_jabatan}_level_{$request->level}";
            }

            $role = Role::create([
                'name' => $roleName,
                'guard_name' => 'web'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menyimpan izin akses'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    private function assignPermissionsByLevel($role, $level)
    {
        // Clear existing permissions
        $role->syncPermissions([]);
        
        switch ($level) {
            case '1':
                $role->givePermissionTo(['view']);
                break;
            case '2':
                $role->givePermissionTo(['view', 'create', 'edit']);
                break;
            case '3':
                $role->givePermissionTo(['view', 'create', 'edit', 'delete', 'approve']);
                break;
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

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menghapus izin akses'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus izin akses: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateLevel(Request $request, $id)
    {
        try {
            $role = Role::findOrFail($id);
            $newLevel = $request->level;

            // Update level pada nama role
            $newName = preg_replace('/_level_\d+$/', "_level_{$newLevel}", $role->name);
            $role->update(['name' => $newName]);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengubah level akses'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengubah level akses: ' . $e->getMessage()
            ], 500);
        }
    }
} 