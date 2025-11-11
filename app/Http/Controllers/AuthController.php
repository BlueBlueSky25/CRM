<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = [
            'username' => strtolower(str_replace(' ', '_', $request->username)),
            'password' => $request->password
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();
            
            // Cek apakah user aktif
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'loginError' => 'Akun anda tidak aktif. Mohon hubungi admin untuk aktivasi.',
                ])->withInput();
            }
            
            $request->session()->regenerate();
            
            return redirect()->intended('/dashboard')
                ->with('login_success', 'Selamat datang kembali, ' . $user->username . '! 🎉');
        }

        return back()->withErrors([
            'loginError' => 'Username atau password salah. Silakan coba lagi!',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        \Log::info('Logout triggered');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')
            ->with('success', 'Anda telah berhasil logout. Sampai jumpa! 👋');
    }

    // ========== PASSWORD RESET METHODS ==========

    /**
     * Tampilkan form forgot password
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim link reset password ke email
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar dalam sistem kami.',
        ]);

        // Kirim link reset password
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', '✅ Link reset password telah dikirim ke email Anda! Silakan cek inbox atau spam folder.')
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Tampilkan form reset password
     */
    public function showResetPasswordForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Proses reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password_hash' => Hash::make($password)  // ⚠️ PENTING: gunakan password_hash, bukan password
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', '✅ Password berhasil direset! Silakan login dengan password baru Anda.')
            : back()->withErrors(['email' => [__($status)]]);
    }
}