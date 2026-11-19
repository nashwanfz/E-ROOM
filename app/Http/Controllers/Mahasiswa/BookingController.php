<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    private const MSG = [
        'room_id.required'        => 'Pilih ruangan yang ingin dipinjam.',
        'tanggal.required'        => 'Pilih tanggal peminjaman.',
        'tanggal.date_format'     => 'Format tanggal tidak valid.',
        'tanggal.after_or_equal'  => 'Tanggal peminjaman tidak boleh sebelum hari ini.',
        'jam_mulai.required'      => 'Tentukan jam mulai peminjaman.',
        'jam_mulai.date_format'   => 'Format jam mulai tidak valid.',
        'jam_selesai.required'    => 'Tentukan jam selesai peminjaman.',
        'jam_selesai.date_format' => 'Format jam selesai tidak valid.',
        'jam_selesai.after'       => 'Jam selesai harus setelah jam mulai.',
        'jumlah_peserta.required' => 'Isi perkiraan jumlah peserta.',
        'jumlah_peserta.min'      => 'Jumlah peserta minimal 1 orang.',
        'alasan.required'         => 'Jelaskan alasan atau keperluan peminjaman.',
        'alasan.min'              => 'Alasan peminjaman minimal 10 karakter.',
        'alasan.max'              => 'Alasan peminjaman maksimal 200 karakter.',
    ];

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'room_id'        => ['required', 'exists:rooms,id'],
            'tanggal'        => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'jam_mulai'      => ['required', 'date_format:H:i'],
            'jam_selesai'    => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'jumlah_peserta' => ['required', 'integer', 'min:1'],
            'alasan'         => ['required', 'string', 'min:10', 'max:200'],
        ], self::MSG);

        $room = Room::findOrFail($data['room_id']);

        if (! $room->aktif) {
            return back()->withInput()->withErrors(['room_id' => 'Ruangan ini sedang tidak aktif / tidak dapat dipinjam.']);
        }

        if ($data['jam_mulai'] < '07:00' || $data['jam_selesai'] > '20:00') {
            return back()->withInput()->withErrors(['jam_mulai' => 'Peminjaman ruangan hanya dibuka antara pukul 07:00 sampai 20:00.']);
        }

        if ($data['jumlah_peserta'] > $room->kapasitas) {
            return back()->withInput()->withErrors(['jumlah_peserta' => "Kapasitas {$room->nama} maksimal {$room->kapasitas} orang."]);
        }

        if (Booking::hasConflict($room->id, $data['tanggal'], $data['jam_mulai'], $data['jam_selesai'])) {
            return back()->withInput()->withErrors(['jam_mulai' => "Jadwal bentrok! {$room->nama} sudah dipinjam pada jam tersebut."]);
        }

        Booking::create([
            'user_id'        => auth()->id(),
            'room_id'        => $room->id,
            'tanggal'        => $data['tanggal'],
            'jam_mulai'      => $data['jam_mulai'],
            'jam_selesai'    => $data['jam_selesai'],
            'jumlah_peserta' => $data['jumlah_peserta'],
            'alasan'         => $data['alasan'],
            'status'         => 'menunggu',
            'catatan_admin'  => null,
        ]);

        return back()->with('status', "Pengajuan peminjaman {$room->nama} berhasil dikirim dan menunggu persetujuan admin.");
    }
}
