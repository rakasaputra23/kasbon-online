<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Mail\ResetPasswordMail;
use Symfony\Component\Mailer\Exception\TransportException;

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
        try {
            // Validasi input
            $request->validate([
                'nip' => 'required|string|exists:users,nip',
            ], [
                'nip.required' => 'NIP wajib diisi.',
                'nip.exists' => 'NIP tidak terdaftar dalam sistem.',
            ]);

            $user = User::where('nip', $request->nip)->first();
            
            if (!$user) {
                \Log::error('User not found with NIP: ' . $request->nip);
                return back()->withErrors(['nip' => 'NIP tidak ditemukan.']);
            }

            // Cek apakah user memiliki email
            if (!$user->email) {
                \Log::error('User has no email', ['user_id' => $user->id, 'nip' => $user->nip]);
                return back()->withErrors(['nip' => 'User tidak memiliki email yang terdaftar. Hubungi administrator.']);
            }

            // Generate token
            $token = Str::random(64);
            
            // Delete existing tokens for this user
            DB::table('password_resets')->where('email', $user->email)->delete();
            
            // Create new password reset entry
            DB::table('password_resets')->insert([
                'email' => $user->email,
                'nip' => $user->nip,
                'token' => Hash::make($token),
                'expires_at' => Carbon::now()->addMinutes(60),
                'created_at' => Carbon::now(),
            ]);

            // Create reset URL
            $resetUrl = route('password.reset', [
                'token' => $token,
                'nip' => $user->nip
            ]);

            \Log::info('Preparing to send reset password email', [
                'user_id' => $user->id,
                'nip' => $user->nip,
                'email' => $user->email,
                'mail_driver' => config('mail.default'),
                'mail_host' => config('mail.mailers.smtp.host'),
                'mail_port' => config('mail.mailers.smtp.port'),
                'mail_username' => config('mail.mailers.smtp.username'),
            ]);

            // Send email
            Mail::to($user->email)->send(new ResetPasswordMail($user, $resetUrl, $token));
                
            \Log::info('Reset password email sent successfully', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            return back()->with('status', 'Link reset password telah dikirim ke email ' . $this->maskEmail($user->email) . '. Silakan cek email Anda (termasuk folder spam).');
                
        } catch (TransportException $e) {
            \Log::error('SMTP Transport Error', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'user_nip' => $request->nip ?? 'unknown',
                'mail_config' => [
                    'driver' => config('mail.default'),
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'encryption' => config('mail.mailers.smtp.encryption'),
                ]
            ]);
            
            return back()->withErrors(['email' => 'Gagal mengirim email: Masalah koneksi SMTP. Periksa konfigurasi email.']);
            
        } catch (\Exception $e) {
            \Log::error('Failed to send reset password email', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_nip' => $request->nip ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            
            // Tampilkan error detail jika dalam mode debug
            $errorMessage = 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi administrator.';
            if (config('app.debug')) {
                $errorMessage .= ' Error: ' . $e->getMessage();
            }
            
            return back()->withErrors(['email' => $errorMessage]);
        }
    }

    /**
     * Show reset password form.
     */
    public function showResetPasswordForm(Request $request)
    {
        $token = $request->route('token');
        $nip = $request->query('nip');
        
        if (!$token || !$nip) {
            return redirect()->route('password.request')
                ->withErrors(['token' => 'Link reset password tidak valid.']);
        }

        // Verify token
        $resetData = DB::table('password_resets')
            ->where('nip', $nip)
            ->where('expires_at', '>', Carbon::now())
            ->first();
        
        if (!$resetData || !Hash::check($token, $resetData->token)) {
            return redirect()->route('password.request')
                ->withErrors(['token' => 'Link reset password tidak valid atau sudah kadaluarsa.']);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'nip' => $nip,
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
            'token' => 'required|string',
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.exists' => 'NIP tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'token.required' => 'Token tidak valid.',
        ]);

        // Verify token
        $resetData = DB::table('password_resets')
            ->where('nip', $request->nip)
            ->where('expires_at', '>', Carbon::now())
            ->first();
        
        if (!$resetData || !Hash::check($request->token, $resetData->token)) {
            return back()->withErrors(['token' => 'Token reset password tidak valid atau sudah kadaluarsa.']);
        }

        // Update password
        $user = User::where('nip', $request->nip)->first();
        
        if ($user) {
            $user->update([
                'password' => Hash::make($request->password),
                'updated_at' => Carbon::now(),
            ]);

            // Delete used token
            DB::table('password_resets')->where('nip', $request->nip)->delete();

            return redirect()->route('login')
                ->with('status', 'Password berhasil direset. Silakan login dengan password baru.');
        }

        return back()->withErrors(['nip' => 'User tidak ditemukan.']);
    }

    /**
     * Mask email for privacy
     */
    private function maskEmail($email)
    {
        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1];
        
        $nameLength = strlen($name);
        if ($nameLength <= 2) {
            $maskedName = str_repeat('*', $nameLength);
        } else {
            $maskedName = substr($name, 0, 1) . str_repeat('*', $nameLength - 2) . substr($name, -1);
        }
        
        return $maskedName . '@' . $domain;
    }
}