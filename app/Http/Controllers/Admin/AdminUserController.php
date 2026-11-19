<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    private const MSG = [
        'name.required'     => 'Nama admin wajib diisi.',
        'name.min'          => 'Nama admin minimal 3 karakter.',
        'nim.required'      => 'NIM/NIP admin wajib diisi.',
        'nim.regex'         => 'NIM/NIP harus berupa 8 sampai 12 angka.',
        'nim.unique'        => 'NIM/NIP ini sudah terdaftar.',
        'prodi.required'    => 'Unit kerja / prodi wajib diisi.',
        'password.required' => 'Kata sandi wajib diisi.',
        'password.min'      => 'Kata sandi minimal 8 karakter.',
    ];

    private function rules(?User $user = null): array
    {
        return [
            'name'     => ['required', 'string', 'min:3', 'max:60'],
            'nim'      => ['required', 'regex:/^\d{8,12}$/', Rule::unique('users', 'nim')->ignore($user?->id)],
            'prodi'    => ['required', 'string', 'max:60'],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8', 'max:72'],
        ];
    }

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));

        $admins = User::where('role', 'admin')
            ->when($q !== '', fn ($x) => $x->where(fn ($w) => $w->where('name', 'like', "%{$q}%")->orWhere('nim', 'like', "%{$q}%")))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users_admin', ['admins' => $admins, 'q' => $q]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules(), self::MSG);
        User::create($data + ['role' => 'admin']);

        return back()->with('status', 'Akun admin pengelola berhasil ditambahkan.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->role === 'admin', 404);
        $data = $request->validate($this->rules($user), self::MSG);

        if (empty($data['password'])) {
            unset($data['password']);
        }
        $user->update($data);

        return back()->with('status', 'Data admin berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_unless($user->role === 'admin', 404);
        $user->delete();

        return back()->with('status', 'Akun admin berhasil dihapus.');
    }
}
