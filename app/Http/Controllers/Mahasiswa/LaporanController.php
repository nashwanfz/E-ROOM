<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $mulai = (string) $request->query('mulai', now()->startOfMonth()->toDateString());
        $selesai = (string) $request->query('selesai', now()->endOfMonth()->toDateString());
        $status = $request->query('status');

        $query = Booking::with('room')
            ->where('user_id', auth()->id())
            ->whereDate('tanggal', '>=', $mulai)
            ->whereDate('tanggal', '<=', $selesai);

        if ($status && in_array($status, Booking::STATUS)) {
            $query->where('status', $status);
        }

        $bookings = $query->orderBy('tanggal')->orderBy('jam_mulai')->get();

        $allMyBookings = Booking::where('user_id', auth()->id())
            ->whereDate('tanggal', '>=', $mulai)
            ->whereDate('tanggal', '<=', $selesai)
            ->get();

        $stats = [
            'total'     => $allMyBookings->count(),
            'disetujui' => $allMyBookings->where('status', 'disetujui')->count(),
            'menunggu'  => $allMyBookings->where('status', 'menunggu')->count(),
            'ditolak'   => $allMyBookings->where('status', 'ditolak')->count(),
            'dibatalkan'=> $allMyBookings->where('status', 'dibatalkan')->count(),
        ];

        $allRooms = Room::where('aktif', true)->orderBy('kode')->get();

        return view('mahasiswa.laporan', [
            'bookings'  => $bookings,
            'mulai'     => $mulai,
            'selesai'   => $selesai,
            'status'    => $status,
            'stats'     => $stats,
            'allRooms'  => $allRooms,
        ]);
    }
}
