<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Mail\TwoFactorCodeMail;
use Illuminate\Support\Facades\Mail;
use App\Models\AccessLog;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::validate($credentials)) {
            $user = User::where('email', $credentials['email'])->first();

            if (!$user->estado) {
                return back()->withErrors([
                    'email' => 'Su cuenta ha sido bloqueada. Contacte con el ingeniero de sistemas.',
                ])->onlyInput('email');
            }

            // Generate and send 2FA code
            $this->generateAndSendCode($user);

            // Store user ID in session temporarily
            $request->session()->put('2fa_user_id', $user->id);
            $request->session()->put('2fa_email', $user->email);
            $request->session()->put('2fa_resend_count', 0);

            return redirect()->route('2fa.index');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function showTwoFactorForm(Request $request)
    {
        if (!$request->session()->has('2fa_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor');
    }

    public function verifyTwoFactor(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        if (!$request->session()->has('2fa_user_id')) {
            return redirect()->route('login');
        }

        $user = User::findOrFail($request->session()->get('2fa_user_id'));

        if ($user->two_factor_code === $request->code && now()->lt($user->two_factor_expires_at)) {
            // Reset 2FA fields
            $user->update([
                'two_factor_code' => null,
                'two_factor_expires_at' => null,
                'two_factor_attempts' => 0,
            ]);

            // Log the user in
            Auth::login($user);

            if ($user->role !== 'ing_sistemas') {
                AccessLog::create([
                    'user_id' => $user->id,
                    'login_at' => now()
                ]);
            }

            $request->session()->forget(['2fa_user_id', '2fa_email', '2fa_resend_count']);
            $request->session()->regenerate();

            return redirect()->intended('/admin/dashboard');
        }

        // Increment attempts
        $user->increment('two_factor_attempts');

        if ($user->two_factor_attempts >= 5) {
            $request->session()->forget(['2fa_user_id', '2fa_email', '2fa_resend_count']);
            return redirect()->route('login')->withErrors([
                'email' => 'Demasiados intentos fallidos de verificación. Por favor, inicie sesión de nuevo.',
            ]);
        }

        return back()->withErrors([
            'code' => 'El código de verificación es incorrecto o ha expirado.',
        ]);
    }

    public function resendTwoFactor(Request $request)
    {
        if (!$request->session()->has('2fa_user_id')) {
            return redirect()->route('login');
        }

        $resendCount = $request->session()->get('2fa_resend_count', 0);

        if ($resendCount >= 2) { // Total 3 attempts (1 initial + 2 resends)
            return back()->withErrors([
                'code' => 'Ha alcanzado el límite máximo de reenvíos (3).',
            ]);
        }

        $user = User::findOrFail($request->session()->get('2fa_user_id'));
        $this->generateAndSendCode($user);

        $request->session()->put('2fa_resend_count', $resendCount + 1);

        return back()->with('success', 'Se ha enviado un nuevo código a su correo.');
    }

    private function generateAndSendCode($user)
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $user->update([
            'two_factor_code' => $code,
            'two_factor_expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new TwoFactorCodeMail($code));
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $log = AccessLog::where('user_id', $user->id)->whereNull('logout_at')->latest()->first();
            if ($log) {
                $loginAt = \Carbon\Carbon::parse($log->login_at);
                $duration = $loginAt->diffInSeconds(now());
                $hours = (int) floor($duration / 3600);
                $minutes = (int) floor(($duration % 3600) / 60);
                $seconds = (int) ($duration % 60);
                $formattedDuration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                
                $log->update([
                    'logout_at' => now(),
                    'duration' => $formattedDuration
                ]);
            }
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
