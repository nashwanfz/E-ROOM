@extends('mahasiswa.layout')
@section('title', 'Peminjamanku')
@section('heading', 'Daftar Peminjamanku')

@section('content')
<div class="flex flex-wrap items-end justify-between gap-3">
    <div>
        <h2 class="text-lg font-extrabold">Riwayat & Pengajuan Peminjaman</h2>
        <p class="text-sm text-stone-500">Kelola dan pantau status persetujuan peminjaman ruangan Anda.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('mahasiswa.peminjamanku.ics') }}" class="btn btn-ghost text-xs">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Ekspor Kalender (.ics)
        </a>
        <button type="button" onclick="openPinjamModal()" class="btn btn-primary text-xs">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
            Pinjam Ruangan
        </button>
    </div>
</div>

{{-- Filter Status --}}
<div class="flex flex-wrap gap-2">
    <a href="{{ route('mahasiswa.peminjamanku') }}" class="rounded-xl px-3.5 py-1.5 text-xs font-semibold {{ empty($status) ? 'bg-unmul-600 text-white font-bold' : 'bg-white text-stone-600 border border-stone-200 hover:bg-stone-50 dark:bg-stone-800 dark:border-stone-700 dark:text-stone-300' }}">
        Semua
    </a>
    @foreach (\App\Models\Booking::STATUS as $s)
        <a href="{{ route('mahasiswa.peminjamanku', ['status' => $s]) }}" class="rounded-xl px-3.5 py-1.5 text-xs font-semibold {{ $status === $s ? 'bg-unmul-600 text-white font-bold' : 'bg-white text-stone-600 border border-stone-200 hover:bg-stone-50 dark:bg-stone-800 dark:border-stone-700 dark:text-stone-300' }}">
            {{ ucfirst($s) }}
        </a>
    @endforeach
</div>

{{-- Tabel Peminjamanku --}}
<div class="card overflow-x-auto">
    <table class="w-full min-w-[760px] text-left">
        <thead class="border-b border-stone-200 bg-stone-50 dark:border-stone-800 dark:bg-stone-800/50">
            <tr>
                <th class="th">Tanggal & Waktu</th>
                <th class="th">Ruangan</th>
                <th class="th">Alasan Peminjaman</th>
                <th class="th">Peserta</th>
                <th class="th">Status</th>
                <th class="th">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-200 dark:divide-stone-800">
            @forelse ($bookings as $b)
                <tr>
                    <td class="td whitespace-nowrap">
                        <p class="font-bold">{{ $b->tanggal->format('d/m/Y') }}</p>
                        <p class="text-xs text-stone-500">{{ $b->jam_mulai }} - {{ $b->jam_selesai }} WITA</p>
                    </td>
                    <td class="td">
                        <p class="font-semibold">{{ $b->room->nama ?? '-' }}</p>
                        <p class="text-xs text-stone-500">{{ \App\Models\Room::JENIS[$b->room->jenis ?? ''] ?? ($b->room->jenis ?? '') }}</p>
                    </td>
                    <td class="td max-w-xs">
                        <p class="text-stone-800 dark:text-stone-200">{{ $b->alasan }}</p>
                        @if ($b->catatan_admin)
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">Catatan Admin: {{ $b->catatan_admin }}</p>
                        @endif
                    </td>
                    <td class="td whitespace-nowrap">
                        {{ $b->jumlah_peserta }} orang
                    </td>
                    <td class="td whitespace-nowrap">
                        @if ($b->status === 'disetujui')
                            <span class="inline-block rounded-full bg-green-100 px-2.5 py-1 text-xs font-bold text-green-800 dark:bg-green-900/40 dark:text-green-300">Disetujui</span>
                        @elseif ($b->status === 'menunggu')
                            <span class="inline-block rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">Menunggu</span>
                        @elseif ($b->status === 'ditolak')
                            <span class="inline-block rounded-full bg-red-100 px-2.5 py-1 text-xs font-bold text-red-800 dark:bg-red-900/40 dark:text-red-300">Ditolak</span>
                        @else
                            <span class="inline-block rounded-full bg-stone-200 px-2.5 py-1 text-xs font-bold text-stone-700 dark:bg-stone-700 dark:text-stone-300">Dibatalkan</span>
                        @endif
                    </td>
                    <td class="td whitespace-nowrap">
                        @if (in_array($b->status, ['menunggu', 'disetujui']))
                            <form method="POST" action="{{ route('mahasiswa.peminjamanku.cancel', $b) }}">
                                @csrf @method('PATCH')
                                <button type="button" onclick="showConfirmModal(this.form, 'Batalkan Peminjaman', 'Apakah Anda yakin ingin membatalkan peminjaman ruangan {{ $b->room->nama ?? '' }} pada tanggal {{ $b->tanggal->format('d/m/Y') }}?')" class="btn btn-danger btn-sm">
                                    Batalkan
                                </button>
                            </form>
                        @else
                            <span class="text-xs text-stone-400">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-sm text-stone-500">
                        Belum ada data peminjaman ruangan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $bookings->links() }}
</div>
@endsection
