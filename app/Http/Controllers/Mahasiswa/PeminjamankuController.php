<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PeminjamankuController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $bookings = Booking::with('room')
            ->where('user_id', auth()->id())
            ->when($status && in_array($status, Booking::STATUS), fn ($q) => $q->where('status', $status))
            ->orderByDesc('tanggal')
            ->orderBy('jam_mulai')
            ->paginate(10)
            ->withQueryString();

        $allRooms = Room::where('aktif', true)->orderBy('kode')->get();

        return view('mahasiswa.peminjamanku', [
            'bookings'  => $bookings,
            'status'    => $status,
            'allRooms'  => $allRooms,
        ]);
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === auth()->id(), 403, 'Akses ditolak.');

        if (in_array($booking->status, ['menunggu', 'disetujui'])) {
            $booking->update(['status' => 'dibatalkan']);
            return back()->with('status', 'Peminjaman ruangan berhasil dibatalkan.');
        }

        return back()->withErrors(['booking' => 'Peminjaman ini sudah tidak dapat dibatalkan.']);
    }

    public function exportIcs(): StreamedResponse
    {
        $bookings = Booking::with('room')
            ->where('user_id', auth()->id())
            ->orderBy('tanggal')
            ->get();

        $response = new StreamedResponse(function () use ($bookings) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "BEGIN:VCALENDAR\r\n");
            fputs($handle, "VERSION:2.0\r\n");
            fputs($handle, "PRODID:-//E-ROOM FT Unmul//ID\r\n");

            foreach ($bookings as $b) {
                $tgl = $b->tanggal->format('Ymd');
                $mulai = str_replace(':', '', $b->jam_mulai) . '00';
                $selesai = str_replace(':', '', $b->jam_selesai) . '00';
                $roomName = $b->room->nama ?? 'Ruangan';

                fputs($handle, "BEGIN:VEVENT\r\n");
                fputs($handle, "UID:{$b->id}-eroom-ft-unmul\r\n");
                fputs($handle, "DTSTAMP:" . now()->format('Ymd\THis\Z') . "\r\n");
                fputs($handle, "DTSTART:{$tgl}T{$mulai}\r\n");
                fputs($handle, "DTEND:{$tgl}T{$selesai}\r\n");
                fputs($handle, "SUMMARY:Pinjam {$roomName}\r\n");
                fputs($handle, "DESCRIPTION:{$b->alasan}\r\n");
                fputs($handle, "LOCATION:{$roomName}\r\n");
                fputs($handle, "STATUS:" . strtoupper($b->status) . "\r\n");
                fputs($handle, "END:VEVENT\r\n");
            }

            fputs($handle, "END:VCALENDAR\r\n");
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/calendar; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="peminjaman-eroom.ics"');

        return $response;
    }
}
