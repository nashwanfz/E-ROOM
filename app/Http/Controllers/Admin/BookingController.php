<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    private const MSG = [
        'user_id.required' => 'Pilih mahasiswa peminjam.',
        'room_id.required' => 'Pilih ruangan.',
        'tanggal.required' => 'Isi tanggal peminjaman.',
        'tanggal.date_format' => 'Format tanggal tidak valid.',
        'tanggal.after_or_equal' => 'Tanggal tidak boleh sebelum hari ini.',
        'jam_mulai.required' => 'Isi jam mulai.',
        'jam_selesai.required' => 'Isi jam selesai.',
        'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
        'jumlah_peserta.required' => 'Isi jumlah peserta.',
        'alasan.required' => 'Isi alasan peminjaman.',
        'alasan.min' => 'Alasan peminjaman minimal 10 karakter.',
    ];

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));

        $bookings = Booking::with(['user', 'room'])
            ->when($request->filled('tanggal'), fn ($x) => $x->whereDate('tanggal', $request->query('tanggal')))
            ->when($request->filled('room_id'), fn ($x) => $x->where('room_id', $request->query('room_id')))
            ->when($request->filled('status'), fn ($x) => $x->where('status', $request->query('status')))
            ->when($q !== '', fn ($x) => $x->where(function ($w) use ($q) {
                $w->where('alasan', 'like', "%{$q}%")
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$q}%")->orWhere('nim', 'like', "%{$q}%"));
            }))
            ->orderByDesc('tanggal')->orderBy('jam_mulai')
            ->paginate(15)->withQueryString();

        return view('admin.bookings', [
            'bookings' => $bookings,
            'rooms'    => Room::orderBy('kode')->get(),
            'students' => User::where('role', 'mahasiswa')->orderBy('name')->get(['id', 'name', 'nim']),
            'filters'  => $request->only(['q', 'tanggal', 'room_id', 'status']),
        ]);
    }

    /** Admin menambahkan peminjaman atas nama mahasiswa (langsung disetujui). */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id'        => ['required', 'exists:users,id'],
            'room_id'        => ['required', 'exists:rooms,id'],
            'tanggal'        => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'jam_mulai'      => ['required', 'date_format:H:i'],
            'jam_selesai'    => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'jumlah_peserta' => ['required', 'integer', 'min:1'],
            'alasan'         => ['required', 'string', 'min:10', 'max:200'],
        ], self::MSG);

        $room = Room::findOrFail($data['room_id']);

        if (! $room->aktif) {
            return back()->withInput()->withErrors(['room_id' => 'Ruangan ini sedang dinonaktifkan.']);
        }
        if ($data['jam_mulai'] < '07:00' || $data['jam_selesai'] > '20:00') {
            return back()->withInput()->withErrors(['jam_mulai' => 'Ruangan hanya bisa dipinjam pukul 07:00 sampai 20:00.']);
        }
        if ($data['jumlah_peserta'] > $room->kapasitas) {
            return back()->withInput()->withErrors(['jumlah_peserta' => "{$room->nama} hanya memuat {$room->kapasitas} orang."]);
        }
        if (Booking::hasConflict($room->id, $data['tanggal'], $data['jam_mulai'], $data['jam_selesai'])) {
            return back()->withInput()->withErrors(['jam_mulai' => 'Jadwal bentrok dengan peminjaman lain di ruangan yang sama.']);
        }

        Booking::create($data + ['status' => 'disetujui']);

        return back()->with('status', 'Peminjaman ditambahkan.');
    }

    /** Setujui, tolak, atau batalkan peminjaman. */
    public function status(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'status'        => ['required', 'in:' . implode(',', Booking::STATUS)],
            'catatan_admin' => ['nullable', 'string', 'max:200'],
        ]);

        if ($data['status'] === 'disetujui' && Booking::hasConflict(
            $booking->room_id, $booking->tanggal->toDateString(), $booking->jam_mulai, $booking->jam_selesai, $booking->id
        )) {
            return back()->withErrors(['status' => 'Tidak bisa disetujui: jadwalnya bentrok dengan peminjaman lain di ruangan yang sama.']);
        }

        $booking->status = $data['status'];
        if (array_key_exists('catatan_admin', $data)) {
            $booking->catatan_admin = $data['catatan_admin'] ?: null;
        }
        $booking->save();

        return back()->with('status', 'Status peminjaman diubah menjadi ' . $data['status'] . '.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();

        return back()->with('status', 'Peminjaman dihapus.');
    }
}