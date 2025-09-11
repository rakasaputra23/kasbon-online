<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'nip' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'nip' => $request->nip,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'nip' => 'NIP atau password salah.',
        ])->onlyInput('nip');
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Show forgot password form.
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send reset password email.
     */
    public function sendResetPasswordEmail(Request $request)
    {
        $request->validate([
            'nip' => 'required|string|exists:users,nip',
        ]);

        // For demo purposes, just redirect back with success message
        return back()->with('status', 'Link reset password telah dikirim ke email Anda.');
    }

    /**
     * Show reset password form.
     */
    public function showResetPasswordForm(Request $request)
    {
        return view('auth.reset-password', [
            'token' => $request->token,
            'nip' => $request->nip,
        ]);
    }

    /**
     * Handle reset password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'nip' => 'required|string|exists:users,nip',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('nip', $request->nip)->first();
        
        if ($user) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('login')->with('status', 'Password berhasil direset. Silakan login dengan password baru.');
        }

        return back()->withErrors(['nip' => 'User tidak ditemukan.']);
    }
}