<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KetersediaanController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = (string) $request->query('tanggal', now()->toDateString());
        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
            $tanggal = now()->toDateString();
        }

        $jamMulai = (string) $request->query('jam_mulai', '08:00');
        $jamSelesai = (string) $request->query('jam_selesai', '10:00');
        $jenis = (string) $request->query('jenis', 'all');
        $kapasitas = (int) $request->query('kapasitas', 0);
        $hanyaTersedia = $request->boolean('tersedia_saja');

        $roomsQuery = Room::where('aktif', true);

        if ($jenis !== 'all' && array_key_exists($jenis, Room::JENIS)) {
            $roomsQuery->where('jenis', $jenis);
        }

        if ($kapasitas > 0) {
            $roomsQuery->where('kapasitas', '>=', $kapasitas);
        }

        $rooms = $roomsQuery->orderBy('kode')->get();

        $bookingsOnDate = Booking::with('user')
            ->whereDate('tanggal', $tanggal)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('room_id');

        // Stats
        $allActiveRooms = Room::where('aktif', true)->get();
        $totalRooms = $allActiveRooms->count();
        $bookedRoomIds = $bookingsOnDate->keys()->all();
        $statBusy = count($bookedRoomIds);
        $statFree = max(0, $totalRooms - $statBusy);
        $statDay = Booking::whereDate('tanggal', $tanggal)->whereIn('status', ['menunggu', 'disetujui'])->count();
        $statMine = Booking::where('user_id', auth()->id())
            ->whereDate('tanggal', '>=', now()->toDateString())
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->count();

        // Process room availability with time window
        $roomsData = $rooms->map(function ($r) use ($bookingsOnDate, $jamMulai, $jamSelesai) {
            $list = $bookingsOnDate->get($r->id, collect());
            $hasClash = false;
            if ($jamMulai && $jamSelesai && $jamMulai < $jamSelesai) {
                $hasClash = $list->contains(function ($b) use ($jamMulai, $jamSelesai) {
                    return $b->jam_mulai < $jamSelesai && $b->jam_selesai > $jamMulai;
                });
            }
            return [
                'room'      => $r,
                'bookings'  => $list,
                'is_free'   => ! $hasClash,
            ];
        });

        if ($hanyaTersedia) {
            $roomsData = $roomsData->filter(fn ($item) => $item['is_free']);
        }

        return view('mahasiswa.ketersediaan', [
            'tanggal'       => $tanggal,
            'jamMulai'      => $jamMulai,
            'jamSelesai'    => $jamSelesai,
            'jenis'         => $jenis,
            'kapasitas'     => $kapasitas,
            'hanyaTersedia' => $hanyaTersedia,
            'roomsData'     => $roomsData,
            'allRooms'      => $allActiveRooms,
            'stats'         => [
                'free'  => $statFree,
                'busy'  => $statBusy,
                'day'   => $statDay,
                'mine'  => $statMine,
            ],
        ]);
    }
}
