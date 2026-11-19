@extends('mahasiswa.layout')
@section('title', 'Ketersediaan Ruangan')
@section('heading', 'Ketersediaan Ruangan')

@section('content')
@php
    $tglLabel = \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y');
    $mins = fn ($t) => ((int) substr($t, 0, 2)) * 60 + (int) substr($t, 3, 2);
    $pos = function ($s, $e) use ($mins) {
        $l = ($mins($s) - 420) / 780 * 100;
        $w = ($mins($e) - $mins($s)) / 780 * 100;
        return 'left:' . max(0, $l) . '%;width:' . max(1.5, $w) . '%';
    };
@endphp

{{-- Ringkasan --}}
<section class="grid grid-cols-2 gap-3 lg:grid-cols-4" aria-label="Ringkasan">
    <div class="card p-4">
        <p class="text-xs text-stone-500">Ruangan tersedia (sesuai filter)</p>
        <p class="mt-1 text-2xl font-extrabold text-unmul-600">{{ $stats['free'] }}</p>
    </div>
    <div class="card p-4">
        <p class="text-xs text-stone-500">Ruangan sedang dipinjam</p>
        <p class="mt-1 text-2xl font-extrabold text-red-600">{{ $stats['busy'] }}</p>
    </div>
    <div class="card p-4">
        <p class="text-xs text-stone-500">Peminjaman pada tanggal ini</p>
        <p class="mt-1 text-2xl font-extrabold">{{ $stats['day'] }}</p>
    </div>
    <div class="card p-4">
        <p class="text-xs text-stone-500">Peminjamanku mendatang</p>
        <p class="mt-1 text-2xl font-extrabold text-ember-600">{{ $stats['mine'] }}</p>
    </div>
</section>

{{-- Filter Ketersediaan --}}
<section class="card p-5" aria-labelledby="filterTitle">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-stone-100 pb-3 dark:border-stone-800">
        <div>
            <h2 id="filterTitle" class="text-base font-extrabold">Filter Jadwal & Ketersediaan</h2>
            <p class="text-xs text-stone-500">Pilih waktu untuk melihat ketersediaan slot ruangan pada {{ $tglLabel }}.</p>
        </div>
        <button type="button" onclick="openPinjamModal('', '{{ $tanggal }}', '{{ $jamMulai }}', '{{ $jamSelesai }}')" class="btn btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
            Ajukan Peminjaman
        </button>
    </div>

    <form method="GET" action="{{ route('mahasiswa.ketersediaan') }}" class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-6">
        <label class="block text-xs font-semibold lg:col-span-2">Tanggal
            <input type="date" name="tanggal" value="{{ $tanggal }}" class="field">
        </label>
        <label class="block text-xs font-semibold">Jam Mulai
            <input type="time" name="jam_mulai" value="{{ $jamMulai }}" min="07:00" max="20:00" class="field">
        </label>
        <label class="block text-xs font-semibold">Jam Selesai
            <input type="time" name="jam_selesai" value="{{ $jamSelesai }}" min="07:00" max="20:00" class="field">
        </label>
        <label class="block text-xs font-semibold">Jenis Ruangan
            <select name="jenis" class="field">
                <option value="all">Semua jenis</option>
                @foreach (\App\Models\Room::JENIS as $k => $v)
                    <option value="{{ $k }}" @selected($jenis === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </label>
        <label class="block text-xs font-semibold">Kapasitas Min.
            <input type="number" name="kapasitas" min="0" value="{{ $kapasitas }}" class="field">
        </label>

        <div class="flex items-center justify-between sm:col-span-2 lg:col-span-6 pt-1">
            <label class="flex items-center gap-2 text-sm font-semibold cursor-pointer">
                <input type="checkbox" name="tersedia_saja" value="1" @checked($hanyaTersedia) class="h-4 w-4 accent-[#f25f0f]">
                Tampilkan hanya ruangan yang tersedia pada jam tersebut
            </label>
            <div class="flex gap-2">
                <a href="{{ route('mahasiswa.ketersediaan') }}" class="btn btn-ghost btn-sm">Reset</a>
                <button type="submit" class="btn btn-green btn-sm">Terapkan Filter</button>
            </div>
        </div>
    </form>
</section>

{{-- Keterangan Warna Slot --}}
<div class="flex flex-wrap items-center gap-4 text-xs text-stone-500">
    <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-4 rounded bg-red-500"></span>Dipinjam orang lain</span>
    <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-4 rounded bg-ember-500"></span>Peminjamanku</span>
    <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-4 rounded border-2 border-dashed border-stone-500"></span>Waktu filter yang kamu cari</span>
</div>

{{-- Daftar Kartu Ruangan --}}
<div class="grid gap-4 md:grid-cols-2 2xl:grid-cols-3">
    @forelse ($roomsData as $item)
        @php
            $r = $item['room'];
            $list = $item['bookings'];
            $isFree = $item['is_free'];
        @endphp
        <article class="card flex flex-col justify-between p-5">
            <div>
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h3 class="font-extrabold text-base">{{ $r->nama }}</h3>
                        <p class="text-xs text-stone-500">
                            {{ \App\Models\Room::JENIS[$r->jenis] ?? $r->jenis }} &bull; {{ $r->kapasitas }} kursi
                        </p>
                    </div>
                    <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $isFree ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300' }}">
                        {{ $isFree ? 'Tersedia' : 'Sedang Dipakai' }}
                    </span>
                </div>

                @if ($r->fasilitas)
                    <p class="mt-2 text-xs text-stone-600 dark:text-stone-400">
                        <span class="font-semibold">Fasilitas:</span> {{ $r->fasilitas }}
                    </p>
                @endif

                {{-- Visual Timeline Jam Operasional 07:00 - 20:00 --}}
                <div class="mt-4">
                    <div class="relative h-3 rounded bg-stone-200 dark:bg-stone-700">
                        @foreach ($list as $b)
                            @php $isMine = ($b->user_id === auth()->id()); @endphp
                            <span class="absolute inset-y-0 rounded-sm {{ $isMine ? 'bg-ember-500' : 'bg-red-500' }}"
                                  style="{{ $pos($b->jam_mulai, $b->jam_selesai) }}"
                                  title="{{ $b->jam_mulai }} - {{ $b->jam_selesai }} {{ $b->user->name }}: {{ $b->alasan }}">
                            </span>
                        @endforeach
                        {{-- Window target filter --}}
                        @if ($jamMulai && $jamSelesai && $jamMulai < $jamSelesai)
                            <span class="absolute -inset-y-0.5 rounded border-2 border-dashed border-stone-700 dark:border-stone-200"
                                  style="{{ $pos($jamMulai, $jamSelesai) }}"
                                  title="Filter pencarian: {{ $jamMulai }} - {{ $jamSelesai }}">
                            </span>
                        @endif
                    </div>
                    <div class="relative mt-1 h-3">
                        @foreach ([7, 10, 13, 16, 20] as $h)
                            <span class="absolute -translate-x-1/2 text-[10px] text-stone-400" style="left: {{ ($h * 60 - 420) / 780 * 100 }}%">
                                {{ str_pad($h, 2, '0', STR_PAD_LEFT) }}
                            </span>
                        @endforeach
                    </div>
                </div>

                {{-- List Peminjaman di Ruangan ini --}}
                @if ($list->isNotEmpty())
                    <div class="mt-3 border-t border-stone-100 pt-2 dark:border-stone-800">
                        <p class="text-xs font-semibold text-stone-500">Jadwal pada tanggal ini:</p>
                        <ul class="mt-1 space-y-1 text-xs">
                            @foreach ($list as $b)
                                <li class="flex items-center gap-2 rounded bg-stone-50 px-2 py-1 dark:bg-stone-800">
                                    <span class="font-bold tabular-nums {{ $b->user_id === auth()->id() ? 'text-ember-600' : 'text-stone-700 dark:text-stone-300' }}">
                                        {{ $b->jam_mulai }} - {{ $b->jam_selesai }}
                                    </span>
                                    <span class="truncate">{{ $b->user->name }}: {{ $b->alasan }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p class="mt-3 text-xs text-stone-400">Belum ada jadwal peminjaman pada tanggal ini.</p>
                @endif
            </div>

            <div class="mt-4 pt-3 border-t border-stone-100 dark:border-stone-800">
                <button type="button" onclick="openPinjamModal('{{ $r->id }}', '{{ $tanggal }}', '{{ $jamMulai }}', '{{ $jamSelesai }}')" class="btn {{ $isFree ? 'btn-primary' : 'btn-ghost' }} w-full text-xs">
                    Pinjam Ruang Ini
                </button>
            </div>
        </article>
    @empty
        <div class="card col-span-full border-dashed p-8 text-center text-sm text-stone-500">
            Tidak ada ruangan yang cocok dengan filter pencarian.
        </div>
    @endforelse
</div>
@endsection
