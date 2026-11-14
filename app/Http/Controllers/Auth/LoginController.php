<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'nim'      => ['required', 'regex:/^\d{8,12}$/'],
            'password' => ['required', 'string'],
        ], [
            'nim.required'      => 'NIM wajib diisi.',
            'nim.regex'         => 'NIM harus berupa 8 sampai 12 angka.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            // Pesan sengaja disamakan agar NIM yang terdaftar tidak bisa ditebak.
            throw ValidationException::withMessages([
                'nim' => 'NIM atau kata sandi salah.',
            ]);
        }

        $request->session()->regenerate();

        $home = $request->user()->role === 'admin'
            ? route('admin.dashboard')
            : route('home');

        return redirect()->intended($home);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Kamu sudah keluar.');
    }
}