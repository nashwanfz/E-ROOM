@extends('mahasiswa.layout')
@section('title', 'Kalender Peminjaman')
@section('heading', 'Kalender Peminjaman Ruangan')

@section('content')
@php
    $prevMonth = $currentDate->copy()->subMonth();
    $nextMonth = $currentDate->copy()->addMonth();

    $firstDayOfWeek = $currentDate->copy()->startOfMonth()->dayOfWeek; // 0 = Sun, 1 = Mon ...
    // Map Sunday (0) to 7 for Monday start
    $startCol = $firstDayOfWeek === 0 ? 7 : $firstDayOfWeek;
    $daysInMonth = $currentDate->daysInMonth;
    $selLabel = \Carbon\Carbon::parse($selectedTanggal)->translatedFormat('l, d F Y');
@endphp

<div class="grid gap-6 xl:grid-cols-[1fr_380px]">

    {{-- Main Calendar Grid --}}
    <section class="card overflow-hidden">
        {{-- Header Bulan & Navigasi --}}
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-stone-200 p-4 dark:border-stone-800">
            <div class="flex items-center gap-1">
                <a href="{{ route('mahasiswa.kalender', ['year' => $prevMonth->year, 'month' => $prevMonth->month, 'tanggal' => $selectedTanggal]) }}"
                   class="rounded-lg border border-stone-200 p-2 hover:bg-stone-50 dark:border-stone-700 dark:hover:bg-stone-800" aria-label="Bulan sebelumnya">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
                </a>
                <a href="{{ route('mahasiswa.kalender', ['year' => $nextMonth->year, 'month' => $nextMonth->month, 'tanggal' => $selectedTanggal]) }}"
                   class="rounded-lg border border-stone-200 p-2 hover:bg-stone-50 dark:border-stone-700 dark:hover:bg-stone-800" aria-label="Bulan berikutnya">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
                </a>
                <a href="{{ route('mahasiswa.kalender', ['year' => now()->year, 'month' => now()->month, 'tanggal' => now()->toDateString()]) }}"
                   class="ml-1 rounded-lg border border-stone-200 px-3 py-1.5 text-xs font-semibold hover:bg-stone-50 dark:border-stone-700 dark:hover:bg-stone-800">
                    Hari ini
                </a>
            </div>

            <h2 class="text-lg font-extrabold">{{ $currentDate->translatedFormat('F Y') }}</h2>
            <p class="text-xs text-stone-500">Klik tanggal untuk melihat agenda peminjaman</p>
        </div>

        {{-- Kalender Table / Grid --}}
        <div class="overflow-x-auto">
            <div class="min-w-[600px]">
                <div class="grid grid-cols-7 border-b border-stone-200 bg-stone-50 text-center text-xs font-semibold text-stone-500 dark:border-stone-800 dark:bg-stone-800/50">
                    <div class="py-2.5">Sen</div>
                    <div class="py-2.5">Sel</div>
                    <div class="py-2.5">Rab</div>
                    <div class="py-2.5">Kam</div>
                    <div class="py-2.5">Jum</div>
                    <div class="py-2.5">Sab</div>
                    <div class="py-2.5 text-red-500">Min</div>
                </div>

                <div class="grid grid-cols-7 divide-x divide-y divide-stone-200 dark:divide-stone-800 border-b border-stone-200 dark:border-stone-800">
                    {{-- Empty offset cells --}}
                    @for ($i = 1; $i < $startCol; $i++)
                        <div class="min-h-[85px] bg-stone-50/50 p-2 dark:bg-stone-900/30"></div>
                    @endfor

                    {{-- Day cells --}}
                    @for ($d = 1; $d <= $daysInMonth; $d++)
                        @php
                            $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $d);
                            $isSelected = ($dateStr === $selectedTanggal);
                            $isToday = ($dateStr === now()->toDateString());
                            $dayBookings = $bookingsByDate->get($dateStr, collect());
                            $count = $dayBookings->count();
                        @endphp
                        <a href="{{ route('mahasiswa.kalender', ['year' => $year, 'month' => $month, 'tanggal' => $dateStr]) }}"
                           class="min-h-[85px] p-2 flex flex-col justify-between transition-colors {{ $isSelected ? 'bg-amber-50/80 ring-2 ring-inset ring-amber-400 dark:bg-amber-950/30' : 'hover:bg-stone-50 dark:hover:bg-stone-800/40' }} {{ $isToday ? 'font-bold' : '' }}">
                            <div class="flex items-center justify-between">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-full text-xs {{ $isToday ? 'bg-unmul-600 text-white font-extrabold' : 'text-stone-700 dark:text-stone-300' }}">
                                    {{ $d }}
                                </span>
                                @if ($count > 0)
                                    <span class="rounded-full bg-unmul-100 px-1.5 py-0.5 text-[10px] font-bold text-unmul-800 dark:bg-unmul-900/60 dark:text-unmul-200">
                                        {{ $count }}
                                    </span>
                                @endif
                            </div>

                            @if ($count > 0)
                                <div class="mt-1 space-y-0.5">
                                    @foreach ($dayBookings->take(2) as $b)
                                        <p class="truncate rounded bg-stone-100 px-1 py-0.5 text-[10px] text-stone-600 dark:bg-stone-800 dark:text-stone-300" title="{{ $b->jam_mulai }} {{ $b->room->nama ?? '' }}">
                                            {{ $b->jam_mulai }} {{ $b->room->nama ?? '' }}
                                        </p>
                                    @endforeach
                                    @if ($count > 2)
                                        <p class="text-[9px] text-stone-400">+{{ $count - 2 }} lainnya</p>
                                    @endif
                                </div>
                            @else
                                <div></div>
                            @endif
                        </a>
                    @endfor
                </div>
            </div>
        </div>
    </section>

    {{-- Side Panels: Agenda on selected date & Upcoming --}}
    <div class="space-y-5">
        {{-- Agenda Harian --}}
        <section class="card p-5" aria-labelledby="agendaHeading">
            <div class="border-b border-stone-100 pb-3 dark:border-stone-800">
                <h3 id="agendaHeading" class="font-extrabold text-base">Agenda Ruangan</h3>
                <p class="text-xs text-stone-500">{{ $selLabel }}</p>
            </div>

            <div class="mt-4 max-h-[380px] space-y-2.5 overflow-y-auto">
                @forelse ($agendaBookings as $b)
                    <div class="rounded-xl border border-stone-200 p-3 dark:border-stone-800">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-xs text-unmul-700 dark:text-unmul-300">{{ $b->room->nama ?? 'Ruangan' }}</span>
                            <span class="rounded-full px-2 py-0.5 text-[10px] font-bold {{ $b->status === 'disetujui' ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300' }}">
                                {{ ucfirst($b->status) }}
                            </span>
                        </div>
                        <p class="mt-1 text-xs font-semibold tabular-nums text-stone-800 dark:text-stone-200">
                            {{ $b->jam_mulai }} - {{ $b->jam_selesai }} &bull; {{ $b->jumlah_peserta }} orang
                        </p>
                        <p class="mt-1 text-xs text-stone-600 dark:text-stone-400">
                            {{ $b->alasan }}
                        </p>
                        <p class="mt-1.5 text-[11px] text-stone-400">
                            Peminjam: {{ $b->user->name ?? '-' }} ({{ $b->user->prodi ?? '-' }})
                        </p>
                    </div>
                @empty
                    <div class="py-6 text-center text-xs text-stone-400">
                        Tidak ada peminjaman pada tanggal ini.
                    </div>
                @endforelse
            </div>

            <div class="mt-4 pt-3 border-t border-stone-100 dark:border-stone-800">
                <button type="button" onclick="openPinjamModal('', '{{ $selectedTanggal }}')" class="btn btn-primary w-full text-xs">
                    + Pinjam Ruangan Pada Tanggal Ini
                </button>
            </div>
        </section>

        {{-- Peminjamanku Mendatang --}}
        <section class="card p-5">
            <div class="border-b border-stone-100 pb-3 dark:border-stone-800">
                <h3 class="font-extrabold text-base">Peminjamanku Mendatang</h3>
                <p class="text-xs text-stone-500">Jadwal ruangan yang akan kamu gunakan</p>
            </div>
            <div class="mt-3 space-y-2">
                @forelse ($myUpcomingBookings as $myB)
                    <div class="rounded-lg bg-stone-50 p-2.5 text-xs dark:bg-stone-800">
                        <div class="flex items-center justify-between font-bold">
                            <span>{{ $myB->room->nama ?? 'Ruangan' }}</span>
                            <span class="text-amber-600">{{ $myB->tanggal->format('d/m/Y') }}</span>
                        </div>
                        <p class="text-stone-500">{{ $myB->jam_mulai }} - {{ $myB->jam_selesai }} &bull; Status: {{ ucfirst($myB->status) }}</p>
                    </div>
                @empty
                    <p class="py-3 text-center text-xs text-stone-400">Belum ada peminjaman mendatang.</p>
                @endforelse
            </div>
        </section>
    </div>

</div>
@endsection
