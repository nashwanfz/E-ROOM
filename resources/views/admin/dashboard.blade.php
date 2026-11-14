@extends('admin.layout')
@section('title', 'Ringkasan')
@section('heading', 'Ringkasan')

@section('content')
@php
    $mins = fn ($t) => ((int) substr($t, 0, 2)) * 60 + (int) substr($t, 3, 2);
    $pos = function ($s, $e) use ($mins) {
        $l = ($mins($s) - 420) / 780 * 100;
        $w = ($mins($e) - $mins($s)) / 780 * 100;
        return 'left:' . max(0, $l) . '%;width:' . max(1.5, $w) . '%';
    };
    $tglLabel = \Carbon\Carbon::parse($tanggal)->format('d/m/Y');
@endphp

<section class="grid grid-cols-2 gap-3 lg:grid-cols-4" aria-label="Ringkasan">
    <div class="card p-4"><p class="text-xs text-stone-500">Ruangan aktif</p><p class="mt-1 text-2xl font-extrabold text-unmul-600">{{ $stats['ruangan'] }}</p></div>
    <div class="card p-4"><p class="text-xs text-stone-500">Peminjaman pada {{ $tglLabel }}</p><p class="mt-1 text-2xl font-extrabold">{{ $stats['tanggal'] }}</p></div>
    <div class="card p-4"><p class="text-xs text-stone-500">Menunggu persetujuan</p><p class="mt-1 text-2xl font-extrabold {{ $stats['menunggu'] ? 'text-amber-600' : '' }}">{{ $stats['menunggu'] }}</p></div>
    <div class="card p-4"><p class="text-xs text-stone-500">Mahasiswa terdaftar</p><p class="mt-1 text-2xl font-extrabold">{{ $stats['mahasiswa'] }}</p></div>
</section>

<div class="grid gap-5 xl:grid-cols-[1fr_380px]">

    {{-- Jadwal ruangan pada tanggal terpilih --}}
    <section aria-labelledby="jadwalTitle">
        <div class="flex flex-wrap items-end gap-3">
            <div class="mr-auto">
                <h2 id="jadwalTitle" class="text-lg font-extrabold">Jadwal ruangan</h2>
                <p class="text-sm text-stone-500">Pemakaian semua ruangan aktif pada {{ $tglLabel }}.</p>
            </div>
            <form method="GET" class="flex items-end gap-2">
                <label class="block text-xs font-semibold">Tanggal
                    <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()" class="field mt-1">
                </label>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost">Hari ini</a>
            </form>
        </div>

        <div class="mt-3 flex flex-wrap items-center gap-4 text-xs text-stone-500">
            <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-4 rounded bg-red-500"></span>Disetujui</span>
            <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-4 rounded bg-amber-500"></span>Menunggu persetujuan</span>
        </div>

        <div class="mt-3 grid gap-4 md:grid-cols-2">
            @forelse ($rooms as $room)
                @php $list = $bookings->get($room->id, collect()); @endphp
                <article class="card p-4">
                    <div class="flex items-start gap-3">
                        <div class="min-w-0">
                            <h3 class="truncate font-extrabold">{{ $room->nama }}</h3>
                            <p class="text-xs text-stone-500">{{ \App\Models\Room::JENIS[$room->jenis] ?? $room->jenis }} &bull; {{ $room->kapasitas }} kursi</p>
                        </div>
                        <span class="ml-auto shrink-0 rounded-full px-2.5 py-1 text-xs font-bold {{ $list->isEmpty() ? 'bg-green-100 text-green-800 dark:bg-green-700/30 dark:text-green-200' : 'bg-stone-200 text-stone-700 dark:bg-stone-700 dark:text-stone-200' }}">
                            {{ $list->isEmpty() ? 'Kosong' : $list->count() . ' jadwal' }}
                        </span>
                    </div>

                    <div class="mt-3">
                        <div class="relative h-3 rounded bg-stone-200 dark:bg-stone-700">
                            @foreach ($list as $b)
                                <span class="absolute inset-y-0 rounded-sm {{ $b->status === 'menunggu' ? 'bg-amber-500' : 'bg-red-500' }}" style="{{ $pos($b->jam_mulai, $b->jam_selesai) }}" title="{{ $b->jam_mulai }}-{{ $b->jam_selesai }} {{ $b->user->name }}"></span>
                            @endforeach
                        </div>
                        <div class="relative mt-1 h-3">
                            @foreach ([7, 10, 13, 16, 20] as $h)
                                <span class="absolute -translate-x-1/2 text-[10px] text-stone-400" style="left: {{ ($h * 60 - 420) / 780 * 100 }}%">{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}</span>
                            @endforeach
                        </div>
                    </div>

                    @if ($list->isNotEmpty())
                        <ul class="mt-2 space-y-1 text-xs">
                            @foreach ($list as $b)
                                <li class="flex gap-2 rounded-md bg-stone-50 px-2 py-1.5 dark:bg-stone-800/60">
                                    <span class="shrink-0 font-bold tabular-nums">{{ $b->jam_mulai }}-{{ $b->jam_selesai }}</span>
                                    <span class="min-w-0 flex-1 truncate"><span class="font-semibold">{{ $b->user->name }}</span>: {{ $b->alasan }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </article>
            @empty
                <p class="card col-span-full border-dashed p-8 text-center text-sm text-stone-500">Belum ada ruangan aktif. Tambahkan lewat menu Ruangan.</p>
            @endforelse
        </div>
    </section>

    {{-- Menunggu persetujuan --}}
    <aside>
        <section class="card p-4" aria-labelledby="pendingTitle">
            <div class="flex items-center gap-2">
                <h2 id="pendingTitle" class="font-extrabold">Menunggu persetujuan</h2>
                <a href="{{ route('admin.peminjaman.index', ['status' => 'menunggu']) }}" class="ml-auto text-xs font-semibold text-unmul-700 hover:underline dark:text-unmul-300">Lihat semua</a>
            </div>
            <ul class="mt-3 space-y-2">
                @forelse ($pending as $b)
                    <li class="rounded-xl border border-stone-200 p-3 dark:border-stone-800">
                        <p class="text-sm font-bold">{{ $b->user->name }} <span class="font-normal text-stone-500">&bull; {{ $b->user->prodi }}</span></p>
                        <p class="mt-0.5 text-sm text-stone-600 dark:text-stone-300">{{ $b->alasan }}</p>
                        <p class="mt-1 text-xs text-stone-500">{{ $b->tanggal->format('d/m/Y') }} &bull; {{ $b->jam_mulai }}-{{ $b->jam_selesai }} &bull; {{ $b->room->nama }} &bull; {{ $b->jumlah_peserta }} peserta</p>
                        <div class="mt-2">@include('admin._booking_actions', ['b' => $b])</div>
                    </li>
                @empty
                    <li class="rounded-xl border border-dashed border-stone-300 p-4 text-center text-sm text-stone-500 dark:border-stone-700">Tidak ada pengajuan yang menunggu.</li>
                @endforelse
            </ul>
        </section>
    </aside>
</div>
@endsection