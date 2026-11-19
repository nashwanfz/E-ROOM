<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $inputField = $request->filled('login') ? 'login' : ($request->filled('email') ? 'email' : 'nim');

        $data = $request->validate([
            $inputField => ['required', 'string'],
            'password'  => ['required', 'string'],
        ], [
            "{$inputField}.required" => 'NIM wajib diisi.',
            'password.required'      => 'Kata sandi wajib diisi.',
        ]);

        $identifier = trim((string) $data[$inputField]);
        $password = (string) $data['password'];

        // Cari user by email (admin/super_admin) atau by NIM (mahasiswa)
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL) || str_contains($identifier, '@')) {
            $user = User::where('email', $identifier)
                ->whereIn('role', ['admin', 'super_admin'])
                ->first();
        } else {
            $user = User::where('nim', $identifier)
                ->where('role', 'mahasiswa')
                ->first();
        }

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                $inputField => 'NIM/Email atau kata sandi salah.',
            ]);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        $home = in_array($user->role, ['admin', 'super_admin'], true)
            ? route('admin.dashboard')
            : route('mahasiswa.ketersediaan');

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
