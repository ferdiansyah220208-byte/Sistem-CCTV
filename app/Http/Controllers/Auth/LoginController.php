<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (!auth()->user()->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda tidak aktif.']);
            }

            ActivityLog::record('login', auth()->user(), 'Admin berhasil login');

            return redirect()->intended(route('admin.dashboard'))
                             ->with('success', 'Selamat datang, ' . auth()->user()->name);
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        ActivityLog::record('logout', auth()->user(), 'Admin logout');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}