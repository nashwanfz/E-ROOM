<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = (string) $request->query('tanggal', now()->toDateString());
        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
            $tanggal = now()->toDateString();
        }

        $aktif = ['menunggu', 'disetujui'];

        return view('admin.dashboard', [
            'tanggal' => $tanggal,
            'rooms'   => Room::where('aktif', true)->orderBy('kode')->get(),
            'bookings' => Booking::with('user')
                ->whereDate('tanggal', $tanggal)->whereIn('status', $aktif)
                ->orderBy('jam_mulai')->get()->groupBy('room_id'),
            'pending' => Booking::with(['user', 'room'])->where('status', 'menunggu')
                ->orderBy('tanggal')->orderBy('jam_mulai')->limit(8)->get(),
            'stats' => [
                'ruangan'   => Room::where('aktif', true)->count(),
                'tanggal'   => Booking::whereDate('tanggal', $tanggal)->whereIn('status', $aktif)->count(),
                'menunggu'  => Booking::where('status', 'menunggu')->count(),
                'mahasiswa' => User::where('role', 'mahasiswa')->count(),
            ],
        ]);
    }
}