<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $mulai = (string) $request->query('mulai', now()->startOfMonth()->toDateString());
        $selesai = (string) $request->query('selesai', now()->endOfMonth()->toDateString());
        $roomId = $request->query('room_id');
        $status = $request->query('status');
        $prodi = $request->query('prodi');

        $query = Booking::with(['user', 'room'])
            ->whereDate('tanggal', '>=', $mulai)
            ->whereDate('tanggal', '<=', $selesai);

        if ($roomId) {
            $query->where('room_id', $roomId);
        }
        if ($status && in_array($status, Booking::STATUS)) {
            $query->where('status', $status);
        }
        if ($prodi) {
            $query->whereHas('user', fn ($u) => $u->where('prodi', $prodi));
        }

        $bookings = $query->orderBy('tanggal')->orderBy('jam_mulai')->get();

        // Overall stats for the selected period
        $baseQuery = Booking::whereDate('tanggal', '>=', $mulai)->whereDate('tanggal', '<=', $selesai);
        $stats = [
            'total'      => (clone $baseQuery)->count(),
            'disetujui'  => (clone $baseQuery)->where('status', 'disetujui')->count(),
            'menunggu'   => (clone $baseQuery)->where('status', 'menunggu')->count(),
            'ditolak'    => (clone $baseQuery)->where('status', 'ditolak')->count(),
            'dibatalkan' => (clone $baseQuery)->where('status', 'dibatalkan')->count(),
        ];

        // Room usage breakdown
        $roomStats = Room::where('aktif', true)
            ->withCount(['bookings' => fn ($b) => $b->whereDate('tanggal', '>=', $mulai)->whereDate('tanggal', '<=', $selesai)->where('status', 'disetujui')])
            ->orderByDesc('bookings_count')
            ->get();

        // Prodi breakdown (useful for super_admin & admin)
        $prodiStats = User::where('role', 'mahasiswa')
            ->selectRaw('prodi, count(*) as count')
            ->groupBy('prodi')
            ->pluck('count', 'prodi');

        return view('admin.laporan', [
            'bookings'   => $bookings,
            'rooms'      => Room::orderBy('kode')->get(),
            'mulai'      => $mulai,
            'selesai'    => $selesai,
            'roomId'     => $roomId,
            'status'     => $status,
            'prodi'      => $prodi,
            'stats'      => $stats,
            'roomStats'  => $roomStats,
            'prodiStats' => $prodiStats,
            'isSuperAdmin' => $request->user()->role === 'super_admin',
        ]);
    }
}
