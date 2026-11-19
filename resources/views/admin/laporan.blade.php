@extends('admin.layout')
@section('title', 'Laporan Peminjaman')
@section('heading', 'Laporan Peminjaman Ruangan')

@section('content')
<div class="flex flex-wrap items-end justify-between gap-3">
    <div>
        <h2 class="text-lg font-extrabold">Rekapitulasi & Laporan Penggunaan Ruangan</h2>
        <p class="text-sm text-stone-500">Laporan operasional peminjaman ruangan Fakultas Teknik Universitas Mulawarman.</p>
    </div>
    <button type="button" onclick="window.print()" class="btn btn-ghost text-xs">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
        Cetak Laporan
    </button>
</div>

{{-- Filter Form --}}
<form method="GET" action="{{ route('admin.laporan.index') }}" class="card grid gap-3 p-4 sm:grid-cols-2 lg:grid-cols-5">
    <label class="block text-xs font-semibold">Tanggal Mulai
        <input type="date" name="mulai" value="{{ $mulai }}" class="field">
    </label>
    <label class="block text-xs font-semibold">Tanggal Selesai
        <input type="date" name="selesai" value="{{ $selesai }}" class="field">
    </label>
    <label class="block text-xs font-semibold">Ruangan
        <select name="room_id" class="field">
            <option value="">Semua ruangan</option>
            @foreach ($rooms as $r)
                <option value="{{ $r->id }}" @selected($roomId == $r->id)>{{ $r->nama }}</option>
            @endforeach
        </select>
    </label>
    <label class="block text-xs font-semibold">Status
        <select name="status" class="field">
            <option value="">Semua status</option>
            @foreach (\App\Models\Booking::STATUS as $s)
                <option value="{{ $s }}" @selected($status === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </label>
    <label class="block text-xs font-semibold">Program Studi
        <select name="prodi" class="field">
            <option value="">Semua prodi</option>
            @foreach (['Informatika', 'Teknik Sipil', 'Teknik Mesin', 'Teknik Elektro', 'Teknik Kimia', 'Teknik Industri', 'Teknik Perkapalan', 'Arsitektur', 'Teknik Lingkungan', 'Teknik Geologi'] as $p)
                <option value="{{ $p }}" @selected($prodi === $p)>{{ $p }}</option>
            @endforeach
        </select>
    </label>
    <div class="flex gap-2 sm:col-span-2 lg:col-span-5 pt-1">
        <button type="submit" class="btn btn-green">Terapkan Filter</button>
        <a href="{{ route('admin.laporan.index') }}" class="btn btn-ghost">Reset</a>
    </div>
</form>

{{-- Ringkasan Statistik --}}
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

<div class="grid gap-5 lg:grid-cols-2">
    {{-- Penggunaan per Ruangan --}}
    <div class="card p-4">
        <h3 class="font-extrabold text-sm mb-3">Statistik Pemakaian Ruangan (Disetujui)</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="border-b border-stone-200 dark:border-stone-800">
                    <tr><th class="py-2">Ruangan</th><th class="py-2">Jenis</th><th class="py-2 text-right">Frekuensi Pakai</th></tr>
                </thead>
                <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                    @foreach ($roomStats as $rs)
                        <tr>
                            <td class="py-2 font-semibold">{{ $rs->nama }}</td>
                            <td class="py-2 text-stone-500">{{ \App\Models\Room::JENIS[$rs->jenis] ?? $rs->jenis }}</td>
                            <td class="py-2 text-right font-bold">{{ $rs->bookings_count }} kali</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Distribusi Mahasiswa per Prodi --}}
    <div class="card p-4">
        <h3 class="font-extrabold text-sm mb-3">Distribusi Akun Mahasiswa per Program Studi</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="border-b border-stone-200 dark:border-stone-800">
                    <tr><th class="py-2">Program Studi</th><th class="py-2 text-right">Jumlah Mahasiswa</th></tr>
                </thead>
                <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                    @foreach ($prodiStats as $pName => $pCount)
                        <tr>
                            <td class="py-2 font-semibold">{{ $pName }}</td>
                            <td class="py-2 text-right font-bold">{{ $pCount }} orang</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Tabel Data Laporan Lengkap --}}
<div class="card overflow-x-auto">
    <div class="p-4 border-b border-stone-200 dark:border-stone-800 bg-stone-50 dark:bg-stone-800/40">
        <h3 class="font-extrabold text-sm">Daftar Peminjaman Ruangan: {{ \Carbon\Carbon::parse($mulai)->format('d/m/Y') }} s.d. {{ \Carbon\Carbon::parse($selesai)->format('d/m/Y') }}</h3>
    </div>
    <table class="w-full min-w-[860px] text-left">
        <thead class="border-b border-stone-200 bg-stone-50 dark:border-stone-800 dark:bg-stone-800/50">
            <tr>
                <th class="th">No.</th>
                <th class="th">Waktu</th>
                <th class="th">Ruangan</th>
                <th class="th">Peminjam</th>
                <th class="th">Keperluan</th>
                <th class="th">Peserta</th>
                <th class="th">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-200 dark:divide-stone-800 text-sm">
            @forelse ($bookings as $idx => $b)
                <tr>
                    <td class="td text-stone-500">{{ $idx + 1 }}</td>
                    <td class="td whitespace-nowrap">
                        <p class="font-bold">{{ $b->tanggal->format('d/m/Y') }}</p>
                        <p class="text-xs text-stone-500">{{ $b->jam_mulai }} - {{ $b->jam_selesai }}</p>
                    </td>
                    <td class="td font-semibold">{{ $b->room->nama ?? '-' }}</td>
                    <td class="td">
                        <p class="font-semibold">{{ $b->user->name ?? '-' }}</p>
                        <p class="text-xs text-stone-500">{{ $b->user->nim ?? '-' }} &bull; {{ $b->user->prodi ?? '-' }}</p>
                    </td>
                    <td class="td max-w-xs">
                        <p>{{ $b->alasan }}</p>
                        @if ($b->catatan_admin)
                            <p class="mt-1 text-xs text-red-600">Catatan: {{ $b->catatan_admin }}</p>
                        @endif
                    </td>
                    <td class="td">{{ $b->jumlah_peserta }} orang</td>
                    <td class="td">@include('admin._status', ['status' => $b->status])</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-sm text-stone-500">
                        Tidak ada data peminjaman yang cocok dengan filter laporan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
