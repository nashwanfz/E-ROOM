<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - E-ROOM Fakultas Teknik Universitas Mulawarman</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        try {
            const t = localStorage.getItem('eroom.theme') || 'light';
            document.documentElement.classList.toggle('dark', t === 'dark');
        } catch (e) {}
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                    colors: {
                        unmul: { 50:'#effaf3', 100:'#d8f3e1', 200:'#b3e6c7', 300:'#80d3a4', 400:'#4ab97d', 500:'#27a062', 600:'#188150', 700:'#146842', 800:'#135336', 900:'#0f4430', 950:'#06261a' },
                        ember: { 50:'#fff4ec', 100:'#ffe6d2', 200:'#ffc9a1', 300:'#ffa564', 400:'#fb7d2e', 500:'#f25f0f', 600:'#d9480a', 700:'#b4350b' }
                    }
                }
            }
        };
    </script>

    @verbatim
    <style type="text/tailwindcss">
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
        @layer components {
            .card { @apply rounded-2xl border border-stone-200 bg-white shadow-sm dark:border-stone-800 dark:bg-stone-900; }
            .btn { @apply inline-flex items-center justify-center gap-1.5 rounded-lg px-3.5 py-2 text-sm font-bold focus:outline-none focus-visible:ring-2 focus-visible:ring-ember-300; }
            .btn-primary { @apply bg-ember-500 text-white hover:bg-ember-600; }
            .btn-green { @apply bg-unmul-600 text-white hover:bg-unmul-700; }
            .btn-ghost { @apply border border-stone-300 hover:bg-stone-50 dark:border-stone-700 dark:hover:bg-stone-800; }
            .btn-danger { @apply text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40; }
            .btn-sm { @apply px-2.5 py-1 text-xs; }
            .field { @apply mt-1 w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm font-normal outline-none focus:border-ember-400 focus:ring-2 focus:ring-ember-200 dark:border-stone-700 dark:bg-stone-800 dark:text-stone-100 dark:focus:ring-ember-700/40; }
            .th { @apply px-3 py-2 text-left text-xs font-semibold text-stone-500; }
            .td { @apply px-3 py-3 align-top text-sm; }
        }
        dialog::backdrop { background: rgba(0, 0, 0, .5); }
        dialog[open] { animation: pop .18s ease-out; }
        @keyframes pop { from { opacity: 0; transform: translateY(8px) scale(.98); } to { opacity: 1; transform: none; } }
        @media (prefers-reduced-motion: reduce) { dialog[open] { animation: none; } }
    </style>
    @endverbatim
</head>

<body class="bg-stone-100 text-stone-800 antialiased dark:bg-stone-950 dark:text-stone-100">
@php
    $navMahasiswa = [
        ['mahasiswa.ketersediaan', 'Ketersediaan Ruangan', 'M3 21h18M5 21V7l7-4 7 4v14M9 9h1m4 0h1M9 13h1m4 0h1M9 17h1m4 0h1'],
        ['mahasiswa.kalender', 'Kalender Peminjaman', 'M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z'],
        ['mahasiswa.peminjamanku', 'Peminjamanku', 'M8 6V5a2 2 0 012-2h4a2 2 0 012 2v1m-12 0h16a1 1 0 011 1v11a2 2 0 01-2 2H5a2 2 0 01-2-2V7a1 1 0 011-1z'],
        ['mahasiswa.laporan', 'Laporan Peminjaman', 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
    ];
@endphp

<div class="flex min-h-screen">

    {{-- ============ SIDEBAR MAHASISWA (desktop) ============ --}}
    <aside class="sticky top-0 hidden h-screen w-64 shrink-0 flex-col bg-unmul-950 text-unmul-50 lg:flex">
        <div class="flex items-center gap-3 px-5 pb-4 pt-6">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white p-1.5">
                <img src="{{ asset('images/logo-unmul.webp') }}" alt="Logo Unmul" class="h-full w-full object-contain"
                     onerror="this.replaceWith(Object.assign(document.createElement('span'),{className:'text-sm font-extrabold text-unmul-700',textContent:'FT'}))">
            </div>
            <div class="leading-tight">
                <p class="text-lg font-extrabold tracking-tight">E-ROOM</p>
                <p class="text-xs text-unmul-200">Fakultas Teknik Universitas Mulawarman</p>
            </div>
        </div>

        <div class="mx-5 mb-3 rounded-xl bg-white/10 px-3 py-2 text-xs text-unmul-200">
            Masuk sebagai <span class="font-semibold text-white">{{ auth()->user()->name }}</span>
            <p class="text-[11px] text-unmul-300">NIM: {{ auth()->user()->nim }} &bull; {{ auth()->user()->prodi }}</p>
        </div>

        <nav class="flex-1 space-y-1.5 px-3" aria-label="Menu mahasiswa">
            <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-wider text-unmul-300">Menu Utama</p>
            @foreach ($navMahasiswa as [$routeName, $label, $icon])
                @php $isActive = request()->routeIs($routeName); @endphp
                <a href="{{ route($routeName) }}" @if ($isActive) aria-current="page" @endif
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors {{ $isActive ? 'bg-white/20 font-bold text-white shadow-sm' : 'hover:bg-white/10 text-unmul-100' }}">
                    <svg class="h-5 w-5 shrink-0 text-unmul-200" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="{{ $icon }}"/></svg>
                    {{ $label }}
                </a>
            @endforeach

            <div class="pt-4">
                <button type="button" onclick="openPinjamModal()" class="flex w-full items-center justify-center gap-2 rounded-lg bg-ember-500 px-4 py-2.5 text-sm font-bold text-white shadow hover:bg-ember-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-ember-300">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                    Pinjam Ruangan
                </button>
            </div>
        </nav>

        <div class="p-4 text-center text-xs text-unmul-300 border-t border-white/10">
            &copy; {{ date('Y') }} Fakultas Teknik Universitas Mulawarman
        </div>
    </aside>

    {{-- ============ MAIN CONTENT AREA ============ --}}
    <div class="flex min-w-0 flex-1 flex-col">
        <header class="sticky top-0 z-20 flex items-center gap-3 border-b border-stone-200 bg-white/85 px-4 py-3 backdrop-blur dark:border-stone-800 dark:bg-stone-900/85 sm:px-6">
            <h1 class="text-base font-extrabold tracking-tight sm:text-xl">@yield('heading', 'E-ROOM Fakultas Teknik Universitas Mulawarman')</h1>

            <button id="btnTheme" class="ml-auto rounded-lg p-2 hover:bg-stone-100 dark:hover:bg-stone-800" aria-label="Ganti tema gelap atau terang">
                <svg class="h-5 w-5 dark:hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12.8A9 9 0 1111.2 3a7 7 0 009.8 9.8z"/></svg>
                <svg class="hidden h-5 w-5 dark:block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M4.9 4.9l1.4 1.4m11.4 11.4 1.4 1.4M2 12h2m16 0h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
            </button>

            {{-- Profile Dropdown with Logout (Task 10) --}}
            <div class="relative">
                <button id="btnProfile" class="flex items-center gap-2 rounded-xl px-2 py-1.5 hover:bg-stone-100 dark:hover:bg-stone-800" aria-haspopup="true">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-unmul-600 text-sm font-bold text-white">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                    <span class="hidden text-sm font-semibold sm:block">{{ auth()->user()->name }}</span>
                    <svg class="h-4 w-4 text-stone-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div id="menuProfile" class="absolute right-0 mt-2 hidden w-64 rounded-xl border border-stone-200 bg-white p-2 text-sm shadow-xl dark:border-stone-700 dark:bg-stone-900 z-50">
                    <div class="border-b border-stone-100 px-3 py-2 dark:border-stone-800">
                        <p class="font-bold text-stone-900 dark:text-stone-100">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-stone-500">NIM: {{ auth()->user()->nim }}</p>
                        <p class="text-xs text-stone-500">{{ auth()->user()->prodi }} &bull; Mahasiswa</p>
                    </div>
                    <div class="pt-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
                                Keluar (Logout)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Mobile navigation bar --}}
        <nav class="flex gap-1 overflow-x-auto border-b border-stone-200 bg-white px-3 py-2 dark:border-stone-800 dark:bg-stone-900 lg:hidden" aria-label="Menu mobile">
            @foreach ($navMahasiswa as [$routeName, $label, $icon])
                @php $isActive = request()->routeIs($routeName); @endphp
                <a href="{{ route($routeName) }}" class="shrink-0 rounded-lg px-3 py-1.5 text-xs font-semibold {{ $isActive ? 'bg-unmul-600 text-white' : 'hover:bg-stone-100 dark:hover:bg-stone-800' }}">
                    {{ $label }}
                </a>
            @endforeach
            <button type="button" onclick="openPinjamModal()" class="shrink-0 rounded-lg bg-ember-500 px-3 py-1.5 text-xs font-bold text-white hover:bg-ember-600">
                + Pinjam
            </button>
        </nav>

        <main id="konten" class="mx-auto w-full max-w-[1400px] flex-1 space-y-6 p-4 sm:p-6">
            @yield('content')
        </main>

        <footer class="border-t border-stone-200 px-6 py-4 text-center text-xs text-stone-500 dark:border-stone-800">
            &copy; {{ date('Y') }} Fakultas Teknik Universitas Mulawarman
        </footer>
    </div>
</div>

{{-- ===================== MODAL PINJAM RUANGAN (Task 3) ===================== --}}
<dialog id="dlgPinjam" class="m-auto w-full max-w-lg rounded-2xl bg-white p-0 text-stone-800 shadow-2xl dark:bg-stone-900 dark:text-stone-100">
    <form method="POST" action="{{ route('mahasiswa.peminjaman.store') }}" class="space-y-4 p-6">
        @csrf
        <div class="flex items-center justify-between border-b border-stone-100 pb-3 dark:border-stone-800">
            <div>
                <h2 class="text-lg font-extrabold">Form Peminjaman Ruangan</h2>
                <p class="text-xs text-stone-500">Ajukan peminjaman ruangan perkuliahan / fasilitas Fakultas Teknik Universitas Mulawarman</p>
            </div>
            <button type="button" onclick="document.getElementById('dlgPinjam').close()" class="rounded-lg p-2 hover:bg-stone-100 dark:hover:bg-stone-800" aria-label="Tutup">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6 6 18"/></svg>
            </button>
        </div>

        <div class="rounded-xl bg-unmul-50 p-3 text-xs text-unmul-900 dark:bg-unmul-950/50 dark:text-unmul-200">
            Peminjam: <span class="font-bold">{{ auth()->user()->name }}</span> ({{ auth()->user()->nim }} - {{ auth()->user()->prodi }})
        </div>

        <div>
            <label class="block text-sm font-semibold">Pilih Ruangan
                <select id="modalRoomId" name="room_id" required class="field">
                    <option value="">-- Pilih Ruangan --</option>
                    @if (isset($allRooms))
                        @foreach ($allRooms as $r)
                            <option value="{{ $r->id }}">{{ $r->nama }} ({{ \App\Models\Room::JENIS[$r->jenis] ?? $r->jenis }} &bull; {{ $r->kapasitas }} kursi)</option>
                        @endforeach
                    @endif
                </select>
            </label>
        </div>

        <div>
            <label class="block text-sm font-semibold">Tanggal Peminjaman
                <input id="modalTanggal" type="date" name="tanggal" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required class="field">
            </label>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <label class="block text-sm font-semibold">Jam Mulai
                <input id="modalJamMulai" type="time" name="jam_mulai" min="07:00" max="20:00" value="08:00" required class="field">
            </label>
            <label class="block text-sm font-semibold">Jam Selesai
                <input id="modalJamSelesai" type="time" name="jam_selesai" min="07:00" max="20:00" value="10:00" required class="field">
            </label>
        </div>

        <div>
            <label class="block text-sm font-semibold">Jumlah Peserta (Orang)
                <input type="number" name="jumlah_peserta" min="1" value="10" required class="field">
            </label>
        </div>

        <div>
            <label class="block text-sm font-semibold">Alasan / Keperluan Peminjaman
                <textarea name="alasan" rows="3" minlength="10" maxlength="200" required class="field" placeholder="Contoh: Rapat kerja persiapan lomba teknologi, perkuliahan pengganti, dll."></textarea>
            </label>
            <span class="text-[11px] text-stone-500">Minimal 10 karakter, maksimal 200 karakter.</span>
        </div>

        <div class="flex justify-end gap-2 border-t border-stone-100 pt-3 dark:border-stone-800">
            <button type="button" onclick="document.getElementById('dlgPinjam').close()" class="btn btn-ghost">Batal</button>
            <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
        </div>
    </form>
</dialog>

{{-- ===================== MODAL VERIFIKASI / KONFIRMASI (Task 4) ===================== --}}
<dialog id="dlgConfirm" class="m-auto w-full max-w-md rounded-2xl bg-white p-0 text-stone-800 shadow-2xl dark:bg-stone-900 dark:text-stone-100">
    <div class="p-6 text-center">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-amber-100 text-amber-600 dark:bg-amber-900/40 dark:text-amber-300">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h3 id="confirmTitle" class="mt-4 text-lg font-bold">Konfirmasi Tindakan</h3>
        <p id="confirmMessage" class="mt-2 text-sm text-stone-600 dark:text-stone-400">Apakah Anda yakin ingin melanjutkan tindakan ini?</p>
        <div class="mt-6 flex justify-center gap-3">
            <button type="button" onclick="document.getElementById('dlgConfirm').close()" class="btn btn-ghost">Batal</button>
            <button type="button" id="btnConfirmAction" class="btn btn-primary">Ya, Lanjutkan</button>
        </div>
    </div>
</dialog>

{{-- ===================== MODAL PESAN STATUS & VALIDASI (Task 4) ===================== --}}
<dialog id="dlgMessage" class="m-auto w-full max-w-md rounded-2xl bg-white p-0 text-stone-800 shadow-2xl dark:bg-stone-900 dark:text-stone-100">
    <div class="p-6 text-center">
        <div id="dlgIconStatus" class="mx-auto flex h-12 w-12 items-center justify-center rounded-full">
            {{-- Icon filled dynamically --}}
        </div>
        <h3 id="dlgTitleStatus" class="mt-4 text-lg font-bold">Pemberitahuan</h3>
        <div id="dlgBodyStatus" class="mt-2 text-sm text-stone-600 dark:text-stone-400"></div>
        <div class="mt-6 flex justify-center">
            <button type="button" onclick="document.getElementById('dlgMessage').close()" class="btn btn-ghost px-6">Tutup</button>
        </div>
    </div>
</dialog>

@verbatim
<script>
(() => {
    // Dropdown Profile Toggle
    const btnProfile = document.getElementById('btnProfile');
    const menuProfile = document.getElementById('menuProfile');
    if (btnProfile && menuProfile) {
        btnProfile.addEventListener('click', (e) => {
            e.stopPropagation();
            menuProfile.classList.toggle('hidden');
        });
        document.addEventListener('click', (e) => {
            if (!menuProfile.contains(e.target) && !btnProfile.contains(e.target)) {
                menuProfile.classList.add('hidden');
            }
        });
    }

    // Tema Dark / Light
    const btnTheme = document.getElementById('btnTheme');
    if (btnTheme) {
        btnTheme.addEventListener('click', () => {
            const dark = !document.documentElement.classList.contains('dark');
            document.documentElement.classList.toggle('dark', dark);
            try { localStorage.setItem('eroom.theme', dark ? 'dark' : 'light'); } catch (e) {}
        });
    }

    // Tutup dialog saat klik di backdrop luar
    document.querySelectorAll('dialog').forEach(dlg => {
        dlg.addEventListener('click', e => {
            if (e.target === dlg) dlg.close();
        });
    });
})();

// Buka Modal Pinjam Ruangan
function openPinjamModal(roomId = '', tanggal = '', jamMulai = '', jamSelesai = '') {
    const dlg = document.getElementById('dlgPinjam');
    if (!dlg) return;
    if (roomId) document.getElementById('modalRoomId').value = roomId;
    if (tanggal) document.getElementById('modalTanggal').value = tanggal;
    if (jamMulai) document.getElementById('modalJamMulai').value = jamMulai;
    if (jamSelesai) document.getElementById('modalJamSelesai').value = jamSelesai;
    dlg.showModal();
}

// Konfirmasi Modal Pop-up (Task 4)
let activeConfirmForm = null;
function showConfirmModal(formElement, title, message) {
    activeConfirmForm = formElement;
    document.getElementById('confirmTitle').textContent = title || 'Konfirmasi Tindakan';
    document.getElementById('confirmMessage').textContent = message || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
    const dlg = document.getElementById('dlgConfirm');
    dlg.showModal();
}
document.getElementById('btnConfirmAction').addEventListener('click', () => {
    if (activeConfirmForm) {
        activeConfirmForm.submit();
    }
    document.getElementById('dlgConfirm').close();
});

// Tampilkan Pesan Status / Validasi di Modal Pop-up
function showModalMessage(type, title, messageHtml) {
    const iconContainer = document.getElementById('dlgIconStatus');
    const titleEl = document.getElementById('dlgTitleStatus');
    const bodyEl = document.getElementById('dlgBodyStatus');

    if (type === 'error') {
        iconContainer.className = 'mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-300';
        iconContainer.innerHTML = '<svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
    } else {
        iconContainer.className = 'mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-green-600 dark:bg-green-900/40 dark:text-green-300';
        iconContainer.innerHTML = '<svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m4.5 12.75 6 6 9-13.5"/></svg>';
    }

    titleEl.textContent = title;
    bodyEl.innerHTML = messageHtml;
    document.getElementById('dlgMessage').showModal();
}
</script>
@endverbatim

@if (session('status'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            showModalMessage('success', 'Berhasil', {!! json_encode(session('status')) !!});
        });
    </script>
@endif

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let list = '<ul class="list-disc list-inside text-left space-y-1 mt-2 text-red-600 dark:text-red-400">';
            @foreach ($errors->all() as $err)
                list += '<li>' + {!! json_encode($err) !!} + '</li>';
            @endforeach
            list += '</ul>';
            showModalMessage('error', 'Perhatian / Validasi', list);
        });
    </script>
@endif

</body>
</html>
