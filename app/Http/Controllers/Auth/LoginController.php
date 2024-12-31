<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginController extends Controller
{
    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Cek dulu apakah user dengan member_id tersebut ada dan jabatannya sesuai
        $user = User::where('member_id', $request->member_id)
                    ->whereBetween('jabatan_id', [2, 28])
                    ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'member_id' => ['Akun tidak ditemukan atau tidak memiliki akses.'],
            ]);
        }

        // Coba login
        if (Auth::attempt([
            'member_id' => $request->member_id,
            'password' => $request->password
        ])) {
            $request->session()->regenerate();
            
            // Redirect langsung ke dashboard
            return redirect()->route('dashboard');
        }

        // Jika gagal, kembalikan pesan error
        throw ValidationException::withMessages([
            'member_id' => ['Kredensial yang diberikan tidak cocok dengan data kami.'],
        ]);
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'member_id' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
