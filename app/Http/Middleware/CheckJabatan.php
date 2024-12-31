<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckJabatan
{
    public function handle(Request $request, Closure $next)
    {
        // Cek jika user belum login, redirect ke halaman login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Cek jabatan user
        $user = Auth::user();
        if ($user && $user->jabatan_id >= 2 && $user->jabatan_id <= 28) {
            return $next($request);
        }

        // Jika tidak memenuhi syarat, logout dan redirect dengan pesan
        Auth::logout();
        return redirect()
            ->route('login')
            ->with('error', 'Anda tidak memiliki akses ke sistem ini.');
    }
} 