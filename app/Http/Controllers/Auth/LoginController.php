<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        if (Auth::attempt($credentials)) {
            if (!Auth::user()->estado) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Su cuenta ha sido bloqueada. Contacte con el ingeniero de sistemas.',
                ])->onlyInput('email');
            }

            if (Auth::user()->role !== 'ing_sistemas') {
                AccessLog::create([
                    'user_id' => Auth::id(),
                    'login_at' => now()
                ]);
            }

            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $log = AccessLog::where('user_id', $user->id)->whereNull('logout_at')->latest()->first();
            if ($log) {
                $duration = now()->diffInSeconds($log->login_at);
                $hours = floor($duration / 3600);
                $minutes = floor(($duration / 60) % 60);
                $seconds = $duration % 60;
                $formattedDuration = "{$hours}h {$minutes}m {$seconds}s";
                
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
