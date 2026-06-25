<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Brute force koruması: IP başına 5 deneme/dakika
        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Çok fazla giriş denemesi. Lütfen {$seconds} saniye bekleyin.",
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);

            // Eski session'ı tamamen temizle ve yenisini başlat
            // (sadece regenerate() Livewire ile 409 çakışmasına yol açabiliyor)
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Tekrar giriş yap (session temizlendi)
            Auth::attempt($credentials, $request->boolean('remember'));

            // Son giriş zamanını güncelle
            Auth::user()->updateQuietly(['last_online' => now()]);

            // IP logla
            \App\Models\LoginLog::create([
                'user_id'    => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status'     => 'success',
            ]);

            return redirect()->intended(route('panel.dashboard'));
        }

        RateLimiter::hit($throttleKey, 60);

        // Başarısız giriş logla
        \App\Models\LoginLog::create([
            'user_id'    => \App\Models\User::where('email', $request->email)->value('id'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status'     => 'failed',
            'reason'     => 'Hatalı şifre',
        ]);

        return back()->withErrors([
            'email' => 'Girdiğiniz bilgiler hatalı.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
