<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KalenderController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) $request->query('year', now()->year);
        $month = (int) $request->query('month', now()->month);

        if ($year < 2000 || $year > 2100) $year = now()->year;
        if ($month < 1 || $month > 12) $month = now()->month;

        $tanggal = (string) $request->query('tanggal', now()->toDateString());
        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
            $tanggal = now()->toDateString();
        }

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // Get bookings count per day for the month
        $monthlyBookings = Booking::whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()])
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->get();

        $bookingsByDate = $monthlyBookings->groupBy(fn ($b) => $b->tanggal->toDateString());

        // Get agenda for the selected date
        $agendaBookings = Booking::with(['room', 'user'])
            ->whereDate('tanggal', $tanggal)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->orderBy('jam_mulai')
            ->get();

        // Mahasiswa's upcoming bookings
        $myUpcomingBookings = Booking::with('room')
            ->where('user_id', auth()->id())
            ->whereDate('tanggal', '>=', now()->toDateString())
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->limit(5)
            ->get();

        $allRooms = Room::where('aktif', true)->orderBy('kode')->get();

        return view('mahasiswa.kalender', [
            'year'               => $year,
            'month'              => $month,
            'currentDate'        => $startDate,
            'selectedTanggal'    => $tanggal,
            'bookingsByDate'     => $bookingsByDate,
            'agendaBookings'     => $agendaBookings,
            'myUpcomingBookings' => $myUpcomingBookings,
            'allRooms'           => $allRooms,
        ]);
    }
}
