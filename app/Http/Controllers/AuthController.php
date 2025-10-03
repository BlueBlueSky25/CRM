<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = [
        'username' => strtolower(str_replace(' ', '', $request->username)),
        'password' => $request->password
    ];

       if (Auth::attempt($credentials, $request->filled('remember'))) {
        $user = Auth::user(); // ambil user yang berhasil login

        // cek apakah user aktif
        if (!$user->is_active) {
            Auth::logout();

            return back()->withErrors([
                'loginError' => '❌ Akun anda tidak aktif. Mohon hubungi admin untuk aktivasi.',
            ]);
        }

        // kalau aktif, lanjut login
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

        return back()->withErrors([
            'loginError' => 'Username atau password salah!',
        ]);
    }

   public function logout(Request $request)
{
    \Log::info('Logout triggered');
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
}
}
