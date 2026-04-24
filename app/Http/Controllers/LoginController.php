<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
     /**
     * Tampilkan halaman login
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        // validasi input
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // coba login pakai username & password
        if (Auth::attempt([
            'username' => $credentials['username'],
            'password' => $credentials['password'],
        ], $request->boolean('remember'))) {

            // regenerate session biar aman
            $request->session()->regenerate();

            // redirect ke halaman tujuan (atau dashboard)
            return redirect()->intended('/dashboard');
        }

        // kalau gagal
        return back()->withErrors([
            'username' => 'Username atau password tidak sesuai.',
        ])->onlyInput('username');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
}

}