<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    private const MSG = [
        'kode.required' => 'Kode ruangan wajib diisi.',
        'kode.unique'   => 'Kode ruangan sudah dipakai.',
        'kode.alpha_dash' => 'Kode hanya boleh huruf, angka, strip, dan garis bawah.',
        'nama.required' => 'Nama ruangan wajib diisi.',
        'jenis.in'      => 'Jenis ruangan tidak valid.',
        'kapasitas.required' => 'Kapasitas wajib diisi.',
        'kapasitas.min' => 'Kapasitas minimal 1.',
    ];

    private function rules(?Room $room = null): array
    {
        return [
            'kode'      => ['required', 'alpha_dash', 'max:12', Rule::unique('rooms', 'kode')->ignore($room?->id)],
            'nama'      => ['required', 'string', 'max:60'],
            'jenis'     => ['required', Rule::in(array_keys(Room::JENIS))],
            'kapasitas' => ['required', 'integer', 'min:1', 'max:1000'],
            'fasilitas' => ['nullable', 'string', 'max:200'],
        ];
    }

    public function index()
    {
        return view('admin.rooms', [
            'rooms' => Room::withCount('bookings')->orderBy('kode')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules(), self::MSG);
        $data['aktif'] = $request->boolean('aktif');
        Room::create($data);

        return back()->with('status', 'Ruangan ditambahkan.');
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $data = $request->validate($this->rules($room), self::MSG);
        $data['aktif'] = $request->boolean('aktif');
        $room->update($data);

        return back()->with('status', 'Ruangan diperbarui.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        if ($room->bookings()->exists()) {
            return back()->withErrors(['room' => 'Ruangan ini punya riwayat peminjaman, jadi tidak bisa dihapus. Nonaktifkan saja.']);
        }
        $room->delete();

        return back()->with('status', 'Ruangan dihapus.');
    }
}