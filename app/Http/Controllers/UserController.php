<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;
use App\Models\Jabatan;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        // Pastikan user memiliki permission yang sesuai
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = User::query()
            ->with(['member', 'jabatan', 'divisi']);

        // Filter berdasarkan pencarian nama atau member_id
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('member_id', 'like', "%{$search}%")
                  ->orWhereHas('member', function($q) use ($search) {
                      $q->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        // Filter berdasarkan divisi
        if ($request->filled('divisi')) {
            $query->where('divisi_id', $request->divisi);
        }

        // Filter berdasarkan jabatan
        if ($request->filled('jabatan')) {
            $query->where('jabatan_id', $request->jabatan);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->paginate(10)->withQueryString();
        $roles = Role::all();
        $divisis = Divisi::all();
        $jabatans = Jabatan::all();

        return view('users.index', compact('users', 'roles', 'divisis', 'jabatans'));
    }

    public function create()
    {
        $availableMembers = Member::whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('users')
                      ->whereRaw('users.member_id = members.id_member');
            })
            ->get();

        $jabatans = Jabatan::all();
        
        $divisis = Divisi::all();

        return view('users.create', compact('availableMembers', 'jabatans', 'divisis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|unique:users,member_id',
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'password' => 'required|min:6',
            'jabatan_id' => 'required|exists:jabatans,id_jabatan',
            'divisi_id' => 'required|exists:divisis,id_divisi',
            'status' => 'required|in:aktif,tidak aktif'
        ]);

        // Create member first
        $member = Member::create([
            'id_member' => $validated['member_id'],
            'nama' => $validated['nama'],
            'email' => $validated['email']
        ]);

        // Then create user
        User::create([
            'member_id' => $member->id_member,
            'password' => Hash::make($validated['password']),
            'jabatan_id' => $validated['jabatan_id'],
            'divisi_id' => $validated['divisi_id'],
            'status' => $validated['status']
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        $jabatans = Jabatan::all();
        $divisis = Divisi::all();
        
        return view('users.edit', compact('user', 'jabatans', 'divisis'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'password' => 'nullable|min:6',
            'jabatan_id' => 'required|exists:jabatans,id_jabatan',
            'divisi_id' => 'required|exists:divisis,id_divisi',
            'status' => 'required|in:aktif,tidak aktif'
        ]);

        // Update member
        $user->member->update([
            'nama' => $validated['nama'],
            'email' => $validated['email']
        ]);

        // Update user
        $updateData = [
            'jabatan_id' => $validated['jabatan_id'],
            'divisi_id' => $validated['divisi_id'],
            'status' => $validated['status']
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus');
    }

    public function updateRoles(Request $request, User $user)
    {
        try {
            // Cek jika user adalah admin atau memiliki permission manage roles
            if (!Auth::user()->hasRole('admin') && !Auth::user()->can('manage roles')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk mengubah role'
                ], 403);
            }

            DB::beginTransaction();
            
            $validated = $request->validate([
                'roles' => 'required|array',
                'roles.*' => 'string|exists:roles,name'
            ]);

            // Modifikasi logika pengecekan:
            // 1. Jika target user punya role owner, hanya owner yang bisa mengubah
            // 2. Jika target user punya role admin (tapi bukan owner), admin/owner bisa mengubah
            if ($user->hasRole('owner')) {
                if (!Auth::user()->hasRole('owner')) {
                    throw new \Exception('Hanya Owner yang dapat mengubah role Owner');
                }
            }

            // Sync roles
            $user->syncRoles($validated['roles']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil diupdate'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}