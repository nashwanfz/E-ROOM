<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    private const MSG = [
        'name.required'     => 'Nama wajib diisi.',
        'name.min'          => 'Nama minimal 3 huruf.',
        'nim.required'      => 'NIM wajib diisi.',
        'nim.regex'         => 'NIM harus berupa 8 sampai 12 angka.',
        'nim.unique'        => 'NIM ini sudah terdaftar.',
        'prodi.required'    => 'Program studi wajib diisi.',
        'password.required' => 'Kata sandi wajib diisi.',
        'password.min'      => 'Kata sandi minimal 8 karakter.',
    ];

    private function rules(?User $user = null): array
    {
        return [
            'name'     => ['required', 'string', 'min:3', 'max:60'],
            'nim'      => ['required', 'regex:/^\d{8,12}$/', Rule::unique('users', 'nim')->ignore($user?->id)],
            'prodi'    => ['required', 'string', 'max:40'],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8', 'max:72'],
        ];
    }

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));

        $students = User::withCount('bookings')
            ->where('role', 'mahasiswa')
            ->when($q !== '', fn ($x) => $x->where(fn ($w) => $w->where('name', 'like', "%{$q}%")->orWhere('nim', 'like', "%{$q}%")))
            ->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.students', ['students' => $students, 'q' => $q]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules(), self::MSG);
        User::create($data + ['role' => 'mahasiswa']); // password di-hash otomatis oleh cast 'hashed'

        return back()->with('status', 'Akun mahasiswa ditambahkan.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->role === 'mahasiswa', 404);
        $data = $request->validate($this->rules($user), self::MSG);

        if (empty($data['password'])) {
            unset($data['password']); // kosong = kata sandi tidak diubah
        }
        $user->update($data);

        return back()->with('status', 'Data mahasiswa diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_unless($user->role === 'mahasiswa', 404);
        $user->delete(); // riwayat peminjamannya ikut terhapus

        return back()->with('status', 'Akun mahasiswa dihapus.');
    }
}