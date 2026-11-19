@extends('mahasiswa.layout')
@section('title', 'Laporan Peminjaman')
@section('heading', 'Laporan Peminjaman Mahasiswa')

@section('content')
<div class="flex flex-wrap items-end justify-between gap-3">
    <div>
        <h2 class="text-lg font-extrabold">Laporan Riwayat Peminjaman</h2>
        <p class="text-sm text-stone-500">Rekapitulasi penggunaan ruangan oleh akun Anda di Fakultas Teknik Universitas Mulawarman.</p>
    </div>
    <div class="flex items-center gap-2">
        <button type="button" onclick="window.print()" class="btn btn-ghost text-xs">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak Laporan
        </button>
    </div>
</div>

{{-- Filter Periode & Status --}}
<form method="GET" action="{{ route('mahasiswa.laporan') }}" class="card grid gap-3 p-4 sm:grid-cols-2 lg:grid-cols-4">
    <label class="block text-xs font-semibold">Tanggal Mulai
        <input type="date" name="mulai" value="{{ $mulai }}" class="field">
    </label>
    <label class="block text-xs font-semibold">Tanggal Selesai
        <input type="date" name="selesai" value="{{ $selesai }}" class="field">
    </label>
    <label class="block text-xs font-semibold">Status Peminjaman
        <select name="status" class="field">
            <option value="">Semua status</option>
            @foreach (\App\Models\Booking::STATUS as $s)
                <option value="{{ $s }}" @selected($status === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </label>
    <div class="flex items-end gap-2">
        <button type="submit" class="btn btn-green w-full">Tampilkan Laporan</button>
        <a href="{{ route('mahasiswa.laporan') }}" class="btn btn-ghost">Reset</a>
    </div>
</form>

{{-- Kartu Ringkasan Statistik Laporan --}}
<div class="grid grid-cols-2 gap-3 lg:grid-cols-5">
    <div class="card p-4">
        <p class="text-xs text-stone-500">Total Pengajuan</p>
        <p class="mt-1 text-2xl font-extrabold">{{ $stats['total'] }}</p>
    </div>
    <div class="card p-4">
        <p class="text-xs text-stone-500">Disetujui</p>
        <p class="mt-1 text-2xl font-extrabold text-green-600">{{ $stats['disetujui'] }}</p>
    </div>
    <div class="card p-4">
        <p class="text-xs text-stone-500">Menunggu</p>
        <p class="mt-1 text-2xl font-extrabold text-amber-600">{{ $stats['menunggu'] }}</p>
    </div>
    <div class="card p-4">
        <p class="text-xs text-stone-500">Ditolak</p>
        <p class="mt-1 text-2xl font-extrabold text-red-600">{{ $stats['ditolak'] }}</p>
    </div>
    <div class="card p-4">
        <p class="text-xs text-stone-500">Dibatalkan</p>
        <p class="mt-1 text-2xl font-extrabold text-stone-500">{{ $stats['dibatalkan'] }}</p>
    </div>
</div>

{{-- Tabel Data Laporan --}}
<div class="card overflow-x-auto">
    <div class="p-4 border-b border-stone-200 dark:border-stone-800 bg-stone-50 dark:bg-stone-800/40">
        <h3 class="font-extrabold text-sm">Data Peminjaman: {{ \Carbon\Carbon::parse($mulai)->format('d/m/Y') }} s.d. {{ \Carbon\Carbon::parse($selesai)->format('d/m/Y') }}</h3>
    </div>
    <table class="w-full min-w-[760px] text-left">
        <thead class="border-b border-stone-200 bg-stone-50 dark:border-stone-800 dark:bg-stone-800/50">
            <tr>
                <th class="th">No.</th>
                <th class="th">Tanggal</th>
                <th class="th">Waktu</th>
                <th class="th">Ruangan</th>
                <th class="th">Keperluan / Alasan</th>
                <th class="th">Peserta</th>
                <th class="th">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-200 dark:divide-stone-800 text-sm">
            @forelse ($bookings as $idx => $b)
                <tr>
                    <td class="td text-stone-500">{{ $idx + 1 }}</td>
                    <td class="td font-bold whitespace-nowrap">{{ $b->tanggal->format('d/m/Y') }}</td>
                    <td class="td whitespace-nowrap">{{ $b->jam_mulai }} - {{ $b->jam_selesai }}</td>
                    <td class="td font-semibold">{{ $b->room->nama ?? '-' }}</td>
                    <td class="td max-w-xs">{{ $b->alasan }}</td>
                    <td class="td">{{ $b->jumlah_peserta }} orang</td>
                    <td class="td whitespace-nowrap">
                        @if ($b->status === 'disetujui')
                            <span class="inline-block rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-bold text-green-800 dark:bg-green-900/40 dark:text-green-300">Disetujui</span>
                        @elseif ($b->status === 'menunggu')
                            <span class="inline-block rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-bold text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">Menunggu</span>
                        @elseif ($b->status === 'ditolak')
                            <span class="inline-block rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-bold text-red-800 dark:bg-red-900/40 dark:text-red-300">Ditolak</span>
                        @else
                            <span class="inline-block rounded-full bg-stone-200 px-2.5 py-0.5 text-xs font-bold text-stone-700 dark:bg-stone-700 dark:text-stone-300">Dibatalkan</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-sm text-stone-500">
                        Tidak ada riwayat peminjaman pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
