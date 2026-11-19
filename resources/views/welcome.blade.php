<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="E-ROOM Fakultas Teknik Universitas Mulawarman - peminjaman ruangan untuk mahasiswa.">
    <title>E-ROOM Fakultas Teknik Universitas Mulawarman - Peminjaman Ruangan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
        :root { --sb-bg:#06261a; --sb-fg:#effaf3; --sb-muted:#b3e6c7; --sb-hover:rgba(255,255,255,.1); }
        #sidebar { width: 18rem; transition: width .2s ease; overflow: hidden; }
        #sidebar.collapsed { width: 4.5rem; }
        #sidebar.collapsed .sb-label { display: none; }
        #sidebar.collapsed .sb-head { flex-direction: column; padding: 1rem 0; gap: .5rem; }
        #sidebar.collapsed .sb-toggle { margin-left: 0; }
        #sidebar.collapsed .sb-toggle svg { transform: rotate(180deg); }
        #sidebar.collapsed nav { padding-left: .5rem; padding-right: .5rem; }
        #sidebar.collapsed .nav-item { justify-content: center; padding-left: 0; padding-right: 0; }
        #sidebar.collapsed .sb-group + .sb-group { border-top: 1px solid var(--sb-hover); padding-top: .75rem; }
        .scroll-thin::-webkit-scrollbar { width: 6px; height: 6px; }
        .scroll-thin::-webkit-scrollbar-thumb { background: rgba(120,120,120,.35); border-radius: 9999px; }
        .modal-enter { animation: pop .18s ease-out; }
        @keyframes pop { from { opacity: 0; transform: translateY(8px) scale(.98); } to { opacity: 1; transform: none; } }
        @keyframes toastIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: none; } }
        .toast-enter { animation: toastIn .2s ease-out; }
        @media (prefers-reduced-motion: reduce) { #sidebar, .modal-enter, .toast-enter { animation: none; transition: none; } }
        .day-cell:focus-visible { outline: 2px solid #f25f0f; outline-offset: -2px; }
        .field { margin-top: .25rem; width: 100%; border-radius: .5rem; border: 1px solid #d6d3d1; background: #fff; padding: .5rem .75rem; font-weight: 400; outline: none; }
        .field:focus { border-color: #fb7d2e; box-shadow: 0 0 0 3px rgba(251,125,46,.25); }
        .dark .field { background: #292524; border-color: #44403c; color: #f5f5f4; }
    </style>
    @endverbatim
</head>

<body class="bg-stone-100 text-stone-800 antialiased dark:bg-stone-950 dark:text-stone-100">

<a href="#konten" class="sr-only focus:not-sr-only focus:fixed focus:left-3 focus:top-3 focus:z-[100] focus:rounded-lg focus:bg-ember-500 focus:px-4 focus:py-2 focus:text-white">Lewati ke konten</a>

<div class="flex min-h-screen">

    {{-- ===================== SIDEBAR ===================== --}}
    <aside id="sidebar" class="sticky top-0 z-30 flex h-screen shrink-0 flex-col self-start bg-[var(--sb-bg)] text-[color:var(--sb-fg)]">
        <div class="sb-head flex items-center gap-3 px-5 pb-4 pt-6">
            <div class="flex h-16 w-16 shrink-0 items-center justify-center">
                <img src="{{ asset('images/logo-unmul.webp') }}" alt="Logo Unmul" class="h-full w-full object-contain"
                     onerror="this.replaceWith(Object.assign(document.createElement('span'),{className:'text-sm font-extrabold text-unmul-700',textContent:'FT'}))">
            </div>
            <div class="sb-label leading-tight">
                <p class="text-lg font-extrabold tracking-tight">E-ROOM</p>
                <p class="text-xs text-[color:var(--sb-muted)]">Fakultas Teknik Universitas Mulawarman</p>
            </div>
            <button id="btnToggleSidebar" class="sb-toggle ml-auto rounded-lg p-2 hover:bg-[var(--sb-hover)]" aria-label="Kecilkan sidebar" aria-expanded="true" aria-controls="sidebar" title="Kecilkan / besarkan sidebar">
                <svg class="h-5 w-5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
            </button>
        </div>

        <div class="sb-label mx-5 mb-3 rounded-xl bg-[var(--sb-hover)] px-3 py-2 text-xs text-[color:var(--sb-muted)]">
            Masuk sebagai <span class="font-semibold text-[color:var(--sb-fg)]">Mahasiswa</span>
        </div>

        <nav class="scroll-thin flex-1 space-y-5 overflow-y-auto overflow-x-hidden px-3 pb-4" aria-label="Menu utama">
            <div class="sb-group">
                <p class="sb-label px-3 pb-1 text-xs font-semibold text-[color:var(--sb-muted)]">Peminjaman Ruangan</p>
                <a href="{{ route('mahasiswa.ketersediaan') }}" title="Ketersediaan Ruangan" class="nav-item flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm hover:bg-[var(--sb-hover)]">
                    <svg class="h-5 w-5 shrink-0 text-[color:var(--sb-muted)]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 9h1m4 0h1M9 13h1m4 0h1M9 17h1m4 0h1"/></svg>
                    <span class="sb-label">Ketersediaan Ruangan</span>
                </a>
                <a href="{{ route('mahasiswa.kalender') }}" title="Kalender Peminjaman" class="nav-item flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm hover:bg-[var(--sb-hover)]">
                    <svg class="h-5 w-5 shrink-0 text-[color:var(--sb-muted)]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/></svg>
                    <span class="sb-label">Kalender Peminjaman</span>
                </a>
                <a href="{{ route('mahasiswa.peminjamanku') }}" title="Peminjamanku" class="nav-item flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm hover:bg-[var(--sb-hover)]">
                    <svg class="h-5 w-5 shrink-0 text-[color:var(--sb-muted)]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M8 6V5a2 2 0 012-2h4a2 2 0 012 2v1m-12 0h16a1 1 0 011 1v11a2 2 0 01-2 2H5a2 2 0 01-2-2V7a1 1 0 011-1z"/></svg>
                    <span class="sb-label">Peminjamanku</span>
                </a>
                <a href="{{ route('mahasiswa.laporan') }}" title="Laporan" class="nav-item flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm hover:bg-[var(--sb-hover)]">
                    <svg class="h-5 w-5 shrink-0 text-[color:var(--sb-muted)]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="sb-label">Laporan</span>
                </a>
                <button type="button" onclick="openModal()" title="Pinjam Ruangan" class="nav-item flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm hover:bg-[var(--sb-hover)]">
                    <svg class="h-5 w-5 shrink-0 text-[color:var(--sb-muted)]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                    <span class="sb-label">Pinjam Ruangan</span>
                </button>
            </div>
        </nav>

        <div class="sb-label border-t border-[var(--sb-hover)] p-4">
            <p class="text-xs font-semibold text-[color:var(--sb-muted)]">Warna sidebar</p>
            <div id="sbSwatches" class="mt-2 flex flex-wrap items-center gap-2" role="radiogroup" aria-label="Pilih warna sidebar"></div>
        </div>
    </aside>

    {{-- ===================== MAIN ===================== --}}
    <div class="flex min-w-0 flex-1 flex-col">

        <header class="sticky top-0 z-20 flex items-center gap-3 border-b border-stone-200 bg-white/85 px-4 py-3 backdrop-blur dark:border-stone-800 dark:bg-stone-900/85 sm:px-6">
            <h1 class="text-base font-extrabold tracking-tight sm:text-xl">E-ROOM Fakultas Teknik Universitas Mulawarman</h1>

            <div class="relative ml-auto hidden w-full max-w-xs md:block">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-stone-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                <input id="search" type="search" placeholder="Cari ruangan, peminjam, atau alasan&hellip;" class="w-full rounded-xl border border-stone-200 bg-stone-50 py-2 pl-9 pr-3 text-sm outline-none focus:border-ember-400 focus:ring-2 focus:ring-ember-200 dark:border-stone-700 dark:bg-stone-800 dark:focus:ring-ember-700/40">
            </div>

            <button id="btnTheme" class="ml-auto rounded-lg p-2 hover:bg-stone-100 dark:hover:bg-stone-800 md:ml-0" aria-label="Ganti tema gelap atau terang">
                <svg class="h-5 w-5 dark:hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12.8A9 9 0 1111.2 3a7 7 0 009.8 9.8z"/></svg>
                <svg class="hidden h-5 w-5 dark:block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M4.9 4.9l1.4 1.4m11.4 11.4 1.4 1.4M2 12h2m16 0h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
            </button>

            <div class="relative">
                <button id="btnProfile" class="flex items-center gap-2 rounded-xl px-2 py-1.5 hover:bg-stone-100 dark:hover:bg-stone-800" aria-haspopup="true">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-unmul-600 text-sm font-bold text-white">M</span>
                    <span class="hidden text-sm font-semibold sm:block">Profil Pengguna</span>
                </button>
                <div id="menuProfile" class="absolute right-0 mt-2 hidden w-60 rounded-xl border border-stone-200 bg-white p-1.5 text-sm shadow-xl dark:border-stone-700 dark:bg-stone-900 z-50">
                    <div class="px-3 py-2 border-b border-stone-100 dark:border-stone-800"><p class="font-semibold">Mahasiswa</p><p class="text-xs text-stone-500">Fakultas Teknik Universitas Mulawarman</p></div>
                    <button id="btnExport" class="w-full rounded-lg px-3 py-2 text-left hover:bg-stone-100 dark:hover:bg-stone-800">Ekspor peminjamanku (.ics)</button>
                    <form method="POST" action="{{ route('logout') }}" class="pt-1">
                        @csrf
                        <button type="submit" class="w-full rounded-lg px-3 py-2 text-left font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40">Keluar (Logout)</button>
                    </form>
                </div>
            </div>
        </header>

        <main id="konten" class="mx-auto w-full max-w-[1400px] flex-1 space-y-6 p-4 sm:p-6">

            {{-- Ringkasan --}}
            <section class="grid grid-cols-2 gap-3 lg:grid-cols-4" aria-label="Ringkasan">
                <div class="rounded-2xl border border-stone-200 bg-white p-4 dark:border-stone-800 dark:bg-stone-900">
                    <p class="text-xs text-stone-500">Ruangan tersedia (sesuai filter)</p>
                    <p id="statFree" class="mt-1 text-2xl font-extrabold text-unmul-600">0</p>
                </div>
                <div class="rounded-2xl border border-stone-200 bg-white p-4 dark:border-stone-800 dark:bg-stone-900">
                    <p class="text-xs text-stone-500">Ruangan sedang dipinjam</p>
                    <p id="statBusy" class="mt-1 text-2xl font-extrabold text-red-600">0</p>
                </div>
                <div class="rounded-2xl border border-stone-200 bg-white p-4 dark:border-stone-800 dark:bg-stone-900">
                    <p class="text-xs text-stone-500">Peminjaman pada tanggal ini</p>
                    <p id="statDay" class="mt-1 text-2xl font-extrabold">0</p>
                </div>
                <div class="rounded-2xl border border-stone-200 bg-white p-4 dark:border-stone-800 dark:bg-stone-900">
                    <p class="text-xs text-stone-500">Peminjamanku mendatang</p>
                    <p id="statMine" class="mt-1 text-2xl font-extrabold">0</p>
                </div>
            </section>

            {{-- Ketersediaan ruangan --}}
            <section id="sectAvail" class="scroll-mt-20" aria-labelledby="availTitle">
                <div class="flex flex-wrap items-end gap-3">
                    <div class="mr-auto">
                        <h2 id="availTitle" class="text-lg font-extrabold sm:text-xl">Ketersediaan ruangan</h2>
                        <p id="availSub" class="text-sm text-stone-500"></p>
                    </div>
                    <button id="btnNew" class="inline-flex items-center gap-2 rounded-lg bg-ember-500 px-4 py-2 text-sm font-bold text-white shadow hover:bg-ember-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-ember-300">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                        Pinjam ruangan
                    </button>
                </div>

                <div class="mt-3 grid gap-3 rounded-2xl border border-stone-200 bg-white p-4 dark:border-stone-800 dark:bg-stone-900 sm:grid-cols-2 lg:grid-cols-6">
                    <label class="block text-xs font-semibold lg:col-span-2">Tanggal
                        <input id="qDate" type="date" class="field text-sm">
                    </label>
                    <label class="block text-xs font-semibold">Mulai
                        <input id="qStart" type="time" class="field text-sm">
                    </label>
                    <label class="block text-xs font-semibold">Selesai
                        <input id="qEnd" type="time" class="field text-sm">
                    </label>
                    <label class="block text-xs font-semibold">Jenis ruangan
                        <select id="qKind" class="field text-sm"></select>
                    </label>
                    <label class="block text-xs font-semibold">Kapasitas min.
                        <input id="qCap" type="number" min="0" value="0" class="field text-sm">
                    </label>
                    <label class="flex items-center gap-2 text-sm font-semibold sm:col-span-2 lg:col-span-6">
                        <input id="qFree" type="checkbox" class="h-4 w-4 accent-[#f25f0f]">
                        Tampilkan hanya ruangan yang tersedia
                    </label>
                </div>

                <div class="mt-3 flex flex-wrap items-center gap-4 text-xs text-stone-500">
                    <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-4 rounded bg-red-500"></span>Dipinjam orang lain</span>
                    <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-4 rounded bg-ember-500"></span>Peminjamanku</span>
                    <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-4 rounded border-2 border-dashed border-stone-500"></span>Waktu yang kamu cari</span>
                </div>

                <div id="rooms" class="mt-3 grid gap-4 md:grid-cols-2 2xl:grid-cols-3"></div>
            </section>

            {{-- Kalender + panel --}}
            <div id="sectCal" class="grid scroll-mt-20 gap-5 xl:grid-cols-[1fr_380px]">
                <section class="min-w-0 rounded-2xl border border-stone-200 bg-white shadow-sm dark:border-stone-800 dark:bg-stone-900" aria-label="Kalender peminjaman">
                    <div class="flex flex-wrap items-center gap-2 border-b border-stone-200 p-4 dark:border-stone-800">
                        <div class="flex items-center gap-1">
                            <button id="prev" class="rounded-lg border border-stone-200 p-2 hover:bg-stone-50 dark:border-stone-700 dark:hover:bg-stone-800" aria-label="Bulan sebelumnya"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg></button>
                            <button id="next" class="rounded-lg border border-stone-200 p-2 hover:bg-stone-50 dark:border-stone-700 dark:hover:bg-stone-800" aria-label="Bulan berikutnya"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg></button>
                            <button id="today" class="ml-1 rounded-lg border border-stone-200 px-3 py-2 text-sm font-semibold hover:bg-stone-50 dark:border-stone-700 dark:hover:bg-stone-800">Hari ini</button>
                        </div>
                        <h2 id="monthTitle" class="mr-auto text-lg font-extrabold sm:text-xl"></h2>
                        <p class="text-xs text-stone-500">Klik tanggal untuk melihat ketersediaan</p>
                    </div>
                    <div class="overflow-x-auto scroll-thin">
                        <div class="min-w-[640px]">
                            <div class="grid grid-cols-7 border-b border-stone-200 bg-stone-50 text-center text-xs font-semibold text-stone-500 dark:border-stone-800 dark:bg-stone-800/50">
                                <div class="py-2">Sen</div><div class="py-2">Sel</div><div class="py-2">Rab</div><div class="py-2">Kam</div><div class="py-2">Jum</div><div class="py-2">Sab</div><div class="py-2 text-red-500">Min</div>
                            </div>
                            <div id="grid" class="grid grid-cols-7"></div>
                        </div>
                    </div>
                </section>

                <div class="space-y-5">
                    <section class="rounded-2xl border border-stone-200 bg-white p-4 shadow-sm dark:border-stone-800 dark:bg-stone-900" aria-label="Daftar peminjaman pada tanggal terpilih">
                        <h3 id="agendaTitle" class="font-extrabold"></h3>
                        <p id="agendaSub" class="text-xs text-stone-500"></p>
                        <ul id="agenda" class="mt-3 max-h-[420px] space-y-2 overflow-y-auto scroll-thin"></ul>
                    </section>
                    <section id="panelMine" class="scroll-mt-20 rounded-2xl border border-stone-200 bg-white p-4 shadow-sm dark:border-stone-800 dark:bg-stone-900" aria-label="Peminjamanku">
                        <h3 class="font-extrabold">Peminjamanku</h3>
                        <ul id="mine" class="mt-3 space-y-2"></ul>
                    </section>
                </div>
            </div>
        </main>

        <footer class="border-t border-stone-200 px-6 py-4 text-center text-xs text-stone-500 dark:border-stone-800">
            &copy; <span id="year"></span> Fakultas Teknik Universitas Mulawarman
        </footer>
    </div>
</div>

{{-- ===================== MODAL PINJAM ===================== --}}
<div id="modal" class="fixed inset-0 z-50 hidden items-end justify-center bg-black/50 p-0 sm:items-center sm:p-4" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <form id="form" class="modal-enter max-h-[94vh] w-full max-w-2xl overflow-y-auto rounded-t-2xl bg-white p-5 shadow-2xl dark:bg-stone-900 sm:rounded-2xl" novalidate>
        <div class="flex items-center justify-between">
            <div>
                <h2 id="modalTitle" class="text-lg font-extrabold">Form peminjaman ruangan</h2>
                <p class="text-xs text-stone-500">Nama, program studi, dan alasanmu akan terlihat oleh mahasiswa lain pada ruangan yang kamu pinjam.</p>
            </div>
            <button type="button" id="btnClose" class="rounded-lg p-2 hover:bg-stone-100 dark:hover:bg-stone-800" aria-label="Tutup"><svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6 6 18"/></svg></button>
        </div>

        <fieldset class="mt-4">
            <legend class="text-sm font-extrabold text-unmul-700 dark:text-unmul-300">1. Identitas peminjam</legend>
            <div class="mt-2 grid gap-3 sm:grid-cols-2">
                <label class="block text-sm font-semibold">Nama lengkap
                    <input id="fName" required maxlength="60" autocomplete="name" class="field" placeholder="Sesuai KTM">
                </label>
                <label class="block text-sm font-semibold">NIM
                    <input id="fNim" required inputmode="numeric" maxlength="12" class="field" placeholder="Contoh: 2509106001">
                </label>
                <label class="block text-sm font-semibold">Program studi
                    <input id="fProdi" required list="prodiList" maxlength="40" class="field" placeholder="Pilih atau ketik">
                    <datalist id="prodiList">
                        <option value="Informatika"><option value="Teknik Sipil"><option value="Teknik Mesin"><option value="Teknik Elektro">
                        <option value="Teknik Kimia"><option value="Teknik Industri"><option value="Teknik Perkapalan"><option value="Arsitektur">
                        <option value="Teknik Lingkungan"><option value="Teknik Geologi">
                    </datalist>
                </label>
                <label class="block text-sm font-semibold">No. HP / WhatsApp
                    <input id="fHp" required type="tel" inputmode="tel" autocomplete="tel" class="field" placeholder="08xxxxxxxxxx">
                </label>
            </div>
        </fieldset>

        <fieldset class="mt-5">
            <legend class="text-sm font-extrabold text-unmul-700 dark:text-unmul-300">2. Ruangan dan waktu</legend>
            <div class="mt-2 grid gap-3 sm:grid-cols-2">
                <label class="block text-sm font-semibold sm:col-span-2">Ruangan
                    <select id="fRoom" class="field"></select>
                </label>
                <label class="block text-sm font-semibold sm:col-span-2">Tanggal
                    <input id="fDate" type="date" required class="field">
                </label>
                <label class="block text-sm font-semibold">Jam mulai
                    <input id="fStart" type="time" required min="07:00" max="20:00" class="field">
                </label>
                <label class="block text-sm font-semibold">Jam selesai
                    <input id="fEnd" type="time" required min="07:00" max="20:00" class="field">
                </label>
                <label class="block text-sm font-semibold">Jumlah peserta
                    <input id="fPax" type="number" min="1" value="10" class="field">
                </label>
            </div>
            <div id="slotInfo" class="mt-2 text-xs text-stone-500"></div>
        </fieldset>

        <fieldset class="mt-5">
            <legend class="text-sm font-extrabold text-unmul-700 dark:text-unmul-300">3. Alasan peminjaman</legend>
            <label class="mt-2 block text-sm font-semibold">Jelaskan keperluanmu
                <textarea id="fReason" required rows="3" maxlength="200" class="field" placeholder="Contoh: Rapat persiapan lomba, kerja kelompok mata kuliah Basis Data, latihan presentasi sidang"></textarea>
            </label>
            <p class="mt-1 text-right text-xs text-stone-500"><span id="reasonCount">0</span>/200</p>
            <label class="mt-1 flex items-start gap-2 text-sm">
                <input id="fAgree" type="checkbox" class="mt-0.5 h-4 w-4 accent-[#f25f0f]">
                <span>Saya bersedia menjaga kebersihan dan fasilitas ruangan serta mengembalikannya seperti semula.</span>
            </label>
        </fieldset>

        <p id="formMsg" class="mt-3 min-h-[1.25rem] text-sm" role="alert"></p>
        <div id="suggest" class="mb-2 hidden rounded-lg bg-unmul-50 p-3 text-sm dark:bg-unmul-950/60"></div>

        <div class="mt-2 flex justify-end gap-2">
            <button type="button" id="btnCancel" class="rounded-lg px-4 py-2 text-sm font-semibold hover:bg-stone-100 dark:hover:bg-stone-800">Batal</button>
            <button type="submit" class="rounded-lg bg-ember-500 px-5 py-2 text-sm font-bold text-white hover:bg-ember-600">Ajukan peminjaman</button>
        </div>
    </form>
</div>

<div id="toasts" class="pointer-events-none fixed bottom-4 right-4 z-[60] space-y-2" aria-live="polite"></div>

@verbatim
<script>
(() => {
    'use strict';

    /* ---------- Data dasar ---------- */
    const OPEN = '07:00', CLOSE = '20:00';
    const KINDS = { kuliah: 'Ruang kuliah', lab: 'Laboratorium', seminar: 'Ruang seminar', aula: 'Aula' };
    const ROOMS = [
        { id: 'A101', name: 'Ruang A.101', kind: 'kuliah',  cap: 40,  fac: ['AC', 'Proyektor', 'Papan tulis'] },
        { id: 'A102', name: 'Ruang A.102', kind: 'kuliah',  cap: 40,  fac: ['AC', 'Proyektor', 'Papan tulis'] },
        { id: 'B201', name: 'Ruang B.201', kind: 'kuliah',  cap: 60,  fac: ['AC', 'Proyektor', 'Sound system'] },
        { id: 'B202', name: 'Ruang B.202', kind: 'kuliah',  cap: 50,  fac: ['AC', 'Proyektor'] },
        { id: 'LAB1', name: 'Lab Komputer 1', kind: 'lab',  cap: 30,  fac: ['AC', '30 PC', 'Proyektor'] },
        { id: 'LAB2', name: 'Lab Jaringan',   kind: 'lab',  cap: 25,  fac: ['AC', 'Perangkat jaringan', 'Proyektor'] },
        { id: 'SEM1', name: 'Ruang Seminar TA 1', kind: 'seminar', cap: 25, fac: ['AC', 'TV 55 inci', 'Meja rapat'] },
        { id: 'SEM2', name: 'Ruang Seminar TA 2', kind: 'seminar', cap: 25, fac: ['AC', 'TV 55 inci', 'Meja rapat'] },
        { id: 'AULA', name: 'Aula Fakultas', kind: 'aula', cap: 200, fac: ['AC', 'Panggung', 'Sound system', 'Proyektor'] },
    ];
    const MONTHS = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const DAYS = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const KEY = 'eroom.bookings.v2', ID_KEY = 'eroom.identity.v1', THEME_KEY = 'eroom.theme';

    /* ---------- Util ---------- */
    const $ = (s, r = document) => r.querySelector(s);
    const $$ = (s, r = document) => [...r.querySelectorAll(s)];
    const pad = n => String(n).padStart(2, '0');
    const iso = d => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
    const fromIso = s => { const [y, m, d] = s.split('-').map(Number); return new Date(y, m - 1, d); };
    const toMin = t => { const [h, m] = t.split(':').map(Number); return h * 60 + m; };
    const room = id => ROOMS.find(r => r.id === id) || { name: id, cap: 0, fac: [], kind: 'kuliah' };
    const esc = s => String(s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const longDate = s => { const d = fromIso(s); return `${DAYS[d.getDay()]}, ${d.getDate()} ${MONTHS[d.getMonth()]} ${d.getFullYear()}`; };
    const uid = () => Math.random().toString(36).slice(2, 10);
    const maskNim = n => n.length > 6 ? n.slice(0, 4) + '\u2022'.repeat(n.length - 7) + n.slice(-3) : n;
    const firstName = n => n.split(' ')[0];
    const bySort = (a, b) => (a.date + a.start).localeCompare(b.date + b.start);

    const TODAY = new Date();
    const todayIso = iso(TODAY);
    const OPEN_M = toMin(OPEN), SPAN = toMin(CLOSE) - OPEN_M;

    /* ---------- State ---------- */
    const state = {
        year: TODAY.getFullYear(), month: TODAY.getMonth(),
        q: { date: todayIso, start: '08:00', end: '10:00', kind: 'all', cap: 0, free: false },
        query: '', bookings: [],
    };

    function seed() {
        const y = TODAY.getFullYear(), m = TODAY.getMonth(), t = TODAY.getDate();
        const d = n => iso(new Date(y, m, t + n));
        const P = (by, nim, prodi) => ({ by, nim, prodi, mine: false });
        return [
            { id: uid(), room: 'A101', date: d(0), start: '07:30', end: '09:30', pax: 30, reason: 'Kuliah pengganti Pemrograman Web', ...P('Rizky Maulana', '2409106031', 'Informatika') },
            { id: uid(), room: 'A101', date: d(0), start: '13:00', end: '15:00', pax: 15, reason: 'Rapat persiapan lomba karya tulis ilmiah', ...P('Sinta Dewi Lestari', '2411106014', 'Teknik Sipil') },
            { id: uid(), room: 'LAB1', date: d(0), start: '09:00', end: '12:00', pax: 25, reason: 'Praktikum tambahan Basis Data', ...P('Andi Pratama', '2409106077', 'Informatika') },
            { id: uid(), room: 'SEM1', date: d(0), start: '10:00', end: '12:00', pax: 12, reason: 'Latihan presentasi sidang skripsi', ...P('Nur Aisyah', '2209106045', 'Informatika') },
            { id: uid(), room: 'B201', date: d(0), start: '08:00', end: '11:00', pax: 55, reason: 'Pelatihan Himpunan Mahasiswa Teknik Mesin', ...P('Fajar Ramadhan', '2311106022', 'Teknik Mesin') },
            { id: uid(), room: 'AULA', date: d(1), start: '08:00', end: '16:00', pax: 150, reason: 'Seminar nasional teknologi hijau', ...P('Panitia BEM FT', '2311106090', 'Teknik Lingkungan') },
            { id: uid(), room: 'A102', date: d(1), start: '09:00', end: '11:00', pax: 20, reason: 'Kerja kelompok tugas Struktur Beton', ...P('Dewi Anggraini', '2411106008', 'Teknik Sipil') },
            { id: uid(), room: 'LAB2', date: d(2), start: '13:00', end: '16:00', pax: 20, reason: 'Simulasi jaringan untuk tugas besar', ...P('Bagas Wicaksono', '2309106112', 'Informatika') },
            { id: uid(), room: 'SEM2', date: d(3), start: '09:00', end: '11:00', pax: 18, reason: 'Diskusi proposal penelitian kelompok', ...P('Maya Putri', '2309106039', 'Teknik Elektro') },
        ];
    }
    function load() {
        try { const raw = localStorage.getItem(KEY); state.bookings = raw ? JSON.parse(raw) : seed(); } catch { state.bookings = seed(); }
        save();
    }
    function save() { try { localStorage.setItem(KEY, JSON.stringify(state.bookings)); } catch {} }
    function getIdentity() { try { return JSON.parse(localStorage.getItem(ID_KEY)) || {}; } catch { return {}; } }
    function setIdentity(v) { try { localStorage.setItem(ID_KEY, JSON.stringify(v)); } catch {} }

    /* ---------- Logika ---------- */
    const overlaps = (a, b) => toMin(a.start) < toMin(b.end) && toMin(b.start) < toMin(a.end);
    const dayOf = (roomId, date) => state.bookings.filter(b => b.room === roomId && b.date === date).sort(bySort);
    const conflictsOf = (c) => state.bookings.filter(b => b.room === c.room && b.date === c.date && b.id !== c.id && overlaps(b, c));
    const qWindowValid = () => state.q.start && state.q.end && toMin(state.q.end) > toMin(state.q.start);

    function roomsView() {
        const q = state.query.trim().toLowerCase(), f = state.q;
        return ROOMS.filter(r => (f.kind === 'all' || r.kind === f.kind) && r.cap >= (f.cap || 0)).map(r => {
            const list = dayOf(r.id, f.date);
            const clash = qWindowValid() ? list.filter(b => overlaps(b, f)) : [];
            return { r, list, clash, free: clash.length === 0 };
        }).filter(v => {
            if (f.free && !v.free) return false;
            if (!q) return true;
            return r_match(v.r, q) || v.list.some(b => `${b.by} ${b.reason} ${b.prodi}`.toLowerCase().includes(q));
        });
    }
    const r_match = (r, q) => `${r.name} ${KINDS[r.kind]}`.toLowerCase().includes(q);

    /* ---------- Render: kartu ruangan ---------- */
    function timeline(v) {
        const pos = (s, e) => { const l = (toMin(s) - OPEN_M) / SPAN * 100, w = (toMin(e) - toMin(s)) / SPAN * 100; return `left:${Math.max(0, l)}%;width:${Math.max(1.5, w)}%`; };
        const blocks = v.list.map(b => `<span class="absolute inset-y-0 rounded-sm ${b.mine ? 'bg-ember-500' : 'bg-red-500'}" style="${pos(b.start, b.end)}" title="${esc(b.start)}-${esc(b.end)} ${esc(b.by)}: ${esc(b.reason)}"></span>`).join('');
        const win = qWindowValid() ? `<span class="absolute -inset-y-1 rounded border-2 border-dashed border-stone-600 dark:border-stone-300" style="${pos(state.q.start, state.q.end)}"></span>` : '';
        const ticks = [7, 10, 13, 16, 20].map(h => `<span class="absolute -translate-x-1/2 text-[10px] text-stone-400" style="left:${(h * 60 - OPEN_M) / SPAN * 100}%">${pad(h)}</span>`).join('');
        return `<div class="mt-3"><div class="relative h-3 rounded bg-stone-200 dark:bg-stone-700">${blocks}${win}</div><div class="relative mt-1 h-3">${ticks}</div></div>`;
    }
    function roomCard(v) {
        const { r, list, clash, free } = v;
        const badge = free ? 'bg-green-100 text-green-800 dark:bg-green-700/30 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-700/30 dark:text-red-200';
        const clashBox = !free ? `
            <div class="mt-2 rounded-lg border border-red-200 bg-red-50 p-3 text-sm dark:border-red-900 dark:bg-red-950/40">
                <p class="text-xs font-bold text-red-700 dark:text-red-300">Dipinjam pada waktu yang kamu pilih</p>
                ${clash.map(b => `<div class="mt-1.5"><p class="font-bold">${esc(b.by)} <span class="font-normal text-stone-500">&bull; ${esc(b.prodi)}</span></p>
                    <p class="text-stone-600 dark:text-stone-300">${esc(b.reason)}</p>
                    <p class="text-xs text-stone-500">${esc(b.start)} - ${esc(b.end)} &bull; ${b.pax} peserta</p></div>`).join('')}
            </div>` : '';
        const sched = list.length ? `<ul class="mt-3 max-h-32 space-y-1 overflow-y-auto scroll-thin text-xs">${list.map(b => `
            <li class="flex gap-2 rounded-md bg-stone-50 px-2 py-1.5 dark:bg-stone-800/60">
                <span class="shrink-0 font-bold tabular-nums">${esc(b.start)}-${esc(b.end)}</span>
                <span class="min-w-0 flex-1 truncate"><span class="font-semibold">${esc(b.by)}</span> (${esc(b.prodi)}): ${esc(b.reason)}</span>
                ${b.mine ? `<button data-del="${b.id}" class="shrink-0 font-bold text-red-600 hover:underline">Batalkan</button>` : ''}
            </li>`).join('')}</ul>` : `<p class="mt-3 text-xs text-stone-500">Belum ada yang meminjam pada ${longDate(state.q.date)}.</p>`;
        return `
        <article class="rounded-2xl border ${free ? 'border-stone-200 dark:border-stone-800' : 'border-red-200 dark:border-red-900/60'} bg-white p-4 shadow-sm dark:bg-stone-900">
            <div class="flex items-start gap-3">
                <div class="min-w-0">
                    <h3 class="truncate font-extrabold">${esc(r.name)}</h3>
                    <p class="text-xs text-stone-500">${KINDS[r.kind]} &bull; ${r.cap} kursi</p>
                </div>
                <span class="ml-auto shrink-0 rounded-full px-2.5 py-1 text-xs font-bold ${badge}">${free ? 'Tersedia' : 'Dipinjam'}</span>
            </div>
            <div class="mt-2 flex flex-wrap gap-1.5">${r.fac.map(f => `<span class="rounded-md bg-stone-100 px-2 py-0.5 text-[11px] font-medium dark:bg-stone-800">${esc(f)}</span>`).join('')}</div>
            ${timeline(v)}${clashBox}${sched}
            <button data-book="${r.id}" class="mt-3 w-full rounded-lg px-3 py-2 text-sm font-bold ${free ? 'bg-unmul-600 text-white hover:bg-unmul-700' : 'border border-stone-300 text-stone-700 hover:bg-stone-50 dark:border-stone-700 dark:text-stone-200 dark:hover:bg-stone-800'}">
                ${free ? 'Pinjam ruangan ini' : 'Cari waktu lain untuk ruangan ini'}
            </button>
        </article>`;
    }
    function renderRooms() {
        const q = state.q, views = roomsView();
        $('#availSub').textContent = qWindowValid()
            ? `${longDate(q.date)}, pukul ${q.start} - ${q.end}`
            : `${longDate(q.date)}. Isi jam mulai dan selesai untuk melihat status tiap ruangan.`;
        $('#rooms').innerHTML = views.length ? views.map(roomCard).join('')
            : `<p class="col-span-full rounded-2xl border border-dashed border-stone-300 p-8 text-center text-sm text-stone-500 dark:border-stone-700">Tidak ada ruangan yang cocok dengan filter. Ubah jam, jenis ruangan, atau kapasitas.</p>`;
        const allV = ROOMS.map(r => ({ r, clash: qWindowValid() ? dayOf(r.id, q.date).filter(b => overlaps(b, q)) : [] }));
        const busy = allV.filter(v => v.clash.length).length;
        $('#statBusy').textContent = busy; $('#statFree').textContent = ROOMS.length - busy;
        $('#statDay').textContent = state.bookings.filter(b => b.date === q.date).length;
    }

    /* ---------- Render: kalender & panel ---------- */
    function renderCalendar() {
        $('#monthTitle').textContent = `${MONTHS[state.month]} ${state.year}`;
        const offset = (new Date(state.year, state.month, 1).getDay() + 6) % 7;
        const start = new Date(state.year, state.month, 1 - offset);
        const byDate = {};
        state.bookings.slice().sort(bySort).forEach(b => (byDate[b.date] ||= []).push(b));
        const cells = [];
        for (let i = 0; i < 42; i++) {
            const d = new Date(start.getFullYear(), start.getMonth(), start.getDate() + i);
            if (i >= 35 && d.getMonth() !== state.month) break;
            const key = iso(d), evs = byDate[key] || [], inMonth = d.getMonth() === state.month;
            const chips = evs.slice(0, 2).map(e => `<span class="block truncate rounded px-1.5 py-0.5 text-[11px] font-semibold ${e.mine ? 'bg-ember-100 text-ember-700 dark:bg-ember-700/30 dark:text-ember-200' : 'bg-unmul-100 text-unmul-800 dark:bg-unmul-700/30 dark:text-unmul-200'}">${esc(e.start)} ${esc(e.room)} &middot; ${esc(firstName(e.by))}</span>`).join('');
            const more = evs.length > 2 ? `<span class="block px-1 text-[11px] font-semibold text-stone-500">+${evs.length - 2} lainnya</span>` : '';
            cells.push(`<button type="button" data-date="${key}" aria-label="${longDate(key)}, ${evs.length} peminjaman"
                class="day-cell flex min-h-[96px] flex-col gap-1 border-b border-r border-stone-200 p-1.5 text-left transition-colors hover:bg-unmul-50 dark:border-stone-800 dark:hover:bg-unmul-950/50 ${key === state.q.date ? 'bg-unmul-50 dark:bg-unmul-950/60' : ''} ${inMonth ? '' : 'opacity-40'}">
                <span class="flex h-6 w-6 items-center justify-center self-end rounded-full text-xs font-bold ${key === todayIso ? 'bg-ember-500 text-white' : ''} ${d.getDay() === 0 && key !== todayIso ? 'text-red-500' : ''}">${d.getDate()}</span>${chips}${more}</button>`);
        }
        $('#grid').innerHTML = cells.join('');
    }
    function bookingItem(e, showRoom, canDel) {
        return `<li class="rounded-xl border border-stone-200 p-3 dark:border-stone-800">
            <div class="flex items-start gap-2">
                <p class="min-w-0 flex-1 text-sm font-bold">${esc(e.by)} <span class="font-normal text-stone-500">&bull; ${esc(e.prodi)}</span></p>
                ${canDel && e.mine ? `<button data-del="${e.id}" class="shrink-0 rounded-lg px-2 py-0.5 text-xs font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40">Batalkan</button>` : ''}
            </div>
            <p class="mt-0.5 text-sm text-stone-600 dark:text-stone-300">${esc(e.reason)}</p>
            <p class="mt-1 text-xs text-stone-500">${esc(e.start)} - ${esc(e.end)} &bull; ${esc(room(e.room).name)} &bull; NIM ${esc(maskNim(e.nim))}</p>
        </li>`;
    }
    function renderAgenda() {
        const q = state.query.trim().toLowerCase();
        const items = state.bookings.filter(b => b.date === state.q.date && (!q || `${b.by} ${b.reason} ${b.prodi} ${room(b.room).name}`.toLowerCase().includes(q))).sort(bySort);
        $('#agendaTitle').textContent = longDate(state.q.date);
        $('#agendaSub').textContent = items.length ? `${items.length} peminjaman` : 'Belum ada peminjaman';
        $('#agenda').innerHTML = items.length ? items.map(e => bookingItem(e, true, true)).join('')
            : `<li class="rounded-xl border border-dashed border-stone-300 p-4 text-center text-sm text-stone-500 dark:border-stone-700">Semua ruangan masih kosong pada tanggal ini.</li>`;
    }
    function renderMine() {
        const mine = state.bookings.filter(b => b.mine && b.date >= todayIso).sort(bySort);
        $('#badgeMine').textContent = mine.length; $('#statMine').textContent = mine.length;
        $('#mine').innerHTML = mine.length ? mine.map(e => `<li class="pt-1 text-xs font-semibold text-stone-500">${longDate(e.date)}</li>${bookingItem(e, true, true)}`).join('')
            : `<li class="rounded-xl border border-dashed border-stone-300 p-4 text-center text-sm text-stone-500 dark:border-stone-700">Kamu belum meminjam ruangan. Pilih ruangan yang tersedia lalu isi form peminjaman.</li>`;
    }
    function renderFilters() {
        $('#qKind').innerHTML = `<option value="all">Semua jenis</option>` + Object.entries(KINDS).map(([k, v]) => `<option value="${k}">${v}</option>`).join('');
        $('#fRoom').innerHTML = ROOMS.map(r => `<option value="${r.id}">${esc(r.name)} (${r.cap} kursi)</option>`).join('');
    }
    function syncInputs() {
        const q = state.q;
        $('#qDate').value = q.date; $('#qStart').value = q.start; $('#qEnd').value = q.end; $('#qKind').value = q.kind; $('#qCap').value = q.cap; $('#qFree').checked = q.free;
    }
    function renderAll() { renderRooms(); renderCalendar(); renderAgenda(); renderMine(); }

    /* ---------- Modal ---------- */
    function openModal(pref = {}) {
        $('#form').reset();
        const id = getIdentity();
        $('#fName').value = id.name || ''; $('#fNim').value = id.nim || ''; $('#fProdi').value = id.prodi || ''; $('#fHp').value = id.hp || '';
        $('#fRoom').value = pref.room || ROOMS[0].id;
        $('#fDate').value = pref.date || state.q.date; $('#fDate').min = todayIso;
        $('#fStart').value = pref.start || state.q.start || '08:00'; $('#fEnd').value = pref.end || state.q.end || '10:00';
        $('#reasonCount').textContent = '0'; $('#formMsg').textContent = ''; $('#suggest').classList.add('hidden');
        updateSlotInfo();
        $('#modal').classList.remove('hidden'); $('#modal').classList.add('flex');
        setTimeout(() => (id.name ? $('#fReason') : $('#fName')).focus(), 30);
    }
    function closeModal() { $('#modal').classList.add('hidden'); $('#modal').classList.remove('flex'); }
    const modalOpen = () => !$('#modal').classList.contains('hidden');

    function updateSlotInfo() {
        const rid = $('#fRoom').value, date = $('#fDate').value, box = $('#slotInfo');
        if (!rid || !date) { box.textContent = ''; return; }
        const list = dayOf(rid, date);
        box.innerHTML = list.length ? `<span class="font-semibold">Sudah dipinjam pada tanggal ini:</span> ` + list.map(b => `${esc(b.start)}-${esc(b.end)} (${esc(firstName(b.by))})`).join(', ') : 'Ruangan ini masih kosong sepanjang hari pada tanggal tersebut.';
    }
    function readForm() {
        return { id: uid(), by: $('#fName').value.trim().replace(/\s+/g, ' '), nim: $('#fNim').value.trim(), prodi: $('#fProdi').value.trim(),
            hp: $('#fHp').value.replace(/[\s-]/g, ''), room: $('#fRoom').value, date: $('#fDate').value, start: $('#fStart').value, end: $('#fEnd').value,
            pax: Number($('#fPax').value) || 0, reason: $('#fReason').value.trim(), mine: true };
    }
    function validate(b) {
        if (b.by.length < 3) return 'Isi nama lengkap (minimal 3 huruf).';
        if (!/^\d{8,12}$/.test(b.nim)) return 'NIM harus berupa 8 sampai 12 angka.';
        if (!b.prodi) return 'Isi program studi.';
        if (!/^(\+62|62|0)8\d{7,12}$/.test(b.hp)) return 'Nomor HP tidak valid. Gunakan format 08xxxxxxxxxx.';
        if (!b.date) return 'Pilih tanggal peminjaman.';
        if (b.date < todayIso) return 'Tanggal tidak boleh sebelum hari ini.';
        if (!b.start || !b.end) return 'Isi jam mulai dan jam selesai.';
        if (toMin(b.start) < OPEN_M || toMin(b.end) > toMin(CLOSE)) return `Ruangan bisa dipinjam pukul ${OPEN} sampai ${CLOSE}.`;
        if (toMin(b.end) <= toMin(b.start)) return 'Jam selesai harus setelah jam mulai.';
        if (b.date === todayIso && toMin(b.start) < TODAY.getHours() * 60 + TODAY.getMinutes()) return 'Jam mulai sudah lewat. Pilih jam setelah sekarang.';
        const r = room(b.room);
        if (b.pax < 1) return 'Isi jumlah peserta.';
        if (b.pax > r.cap) return `${r.name} hanya memuat ${r.cap} orang. Kurangi peserta atau pilih ruangan lain.`;
        if (b.reason.length < 10) return 'Tulis alasan peminjaman dengan jelas (minimal 10 karakter).';
        if (!$('#fAgree').checked) return 'Centang pernyataan tanggung jawab untuk melanjutkan.';
        return '';
    }
    function suggestAlternatives(b, clash) {
        const alts = ROOMS.filter(r => r.cap >= b.pax && !conflictsOf({ ...b, room: r.id }).length);
        const c = clash[0];
        $('#suggest').innerHTML = `<p class="font-semibold">Sudah dipinjam ${esc(c.by)} (${esc(c.prodi)}) pukul ${esc(c.start)} - ${esc(c.end)}: ${esc(c.reason)}</p>` +
            (alts.length ? `<p class="mt-1">Ruangan lain yang kosong pada jam itu:</p><div class="mt-2 flex flex-wrap gap-2">${alts.map(r => `<button type="button" data-pick="${r.id}" class="rounded-full bg-unmul-600 px-3 py-1 text-xs font-bold text-white hover:bg-unmul-700">${esc(r.name)}</button>`).join('')}</div>` : `<p class="mt-1">Tidak ada ruangan kosong yang muat pada jam itu. Coba ubah jam.</p>`);
        $('#suggest').classList.remove('hidden');
    }
    const msg = (t, ok) => { $('#formMsg').className = `mt-3 min-h-[1.25rem] text-sm font-semibold ${ok ? 'text-unmul-700 dark:text-unmul-300' : 'text-red-600'}`; $('#formMsg').textContent = t; };

    $('#form').addEventListener('submit', e => {
        e.preventDefault();
        const b = readForm(); $('#suggest').classList.add('hidden');
        const err = validate(b); if (err) return msg(err, false);
        const clash = conflictsOf(b);
        if (clash.length) { msg('Ruangan sudah dipinjam pada jam tersebut.', false); return suggestAlternatives(b, clash); }
        state.bookings.push(b); save();
        setIdentity({ name: b.by, nim: b.nim, prodi: b.prodi, hp: b.hp });
        Object.assign(state.q, { date: b.date, start: b.start, end: b.end });
        const d = fromIso(b.date); state.year = d.getFullYear(); state.month = d.getMonth();
        closeModal(); syncInputs(); renderAll();
        toast(`${room(b.room).name} berhasil kamu pinjam pada ${b.start} - ${b.end}.`);
    });
    $('#suggest').addEventListener('click', e => { const id = e.target.dataset.pick; if (!id) return; $('#fRoom').value = id; $('#suggest').classList.add('hidden'); updateSlotInfo(); msg('Ruangan diganti. Ajukan lagi untuk melanjutkan.', true); });
    $('#fRoom').onchange = $('#fDate').onchange = updateSlotInfo;
    $('#fReason').oninput = e => { $('#reasonCount').textContent = e.target.value.length; };

    /* ---------- Toast, hapus, ekspor ---------- */
    function toast(text, undo) {
        const el = document.createElement('div');
        el.className = 'toast-enter pointer-events-auto flex items-center gap-3 rounded-xl bg-stone-900 px-4 py-3 text-sm font-medium text-white shadow-xl dark:bg-white dark:text-stone-900';
        el.innerHTML = `<span>${esc(text)}</span>` + (undo ? `<button class="font-bold text-ember-300 dark:text-ember-600">Urungkan</button>` : '');
        if (undo) el.querySelector('button').onclick = () => { undo(); el.remove(); };
        $('#toasts').appendChild(el); setTimeout(() => el.remove(), 5000);
    }
    function removeBooking(id) {
        const i = state.bookings.findIndex(b => b.id === id && b.mine); if (i < 0) return;
        const [gone] = state.bookings.splice(i, 1); save(); renderAll();
        toast('Peminjaman dibatalkan.', () => { state.bookings.splice(i, 0, gone); save(); renderAll(); });
    }
    function exportIcs() {
        const mine = state.bookings.filter(b => b.mine);
        if (!mine.length) return toast('Belum ada peminjaman untuk diekspor.');
        const dt = (d, t) => d.replace(/-/g, '') + 'T' + t.replace(':', '') + '00';
        const L = ['BEGIN:VCALENDAR', 'VERSION:2.0', 'PRODID:-//E-ROOM Fakultas Teknik Universitas Mulawarman//ID'];
        mine.forEach(b => L.push('BEGIN:VEVENT', `UID:${b.id}-eroom-ft-unmul`, `DTSTAMP:${dt(todayIso, '00:00')}`, `DTSTART:${dt(b.date, b.start)}`, `DTEND:${dt(b.date, b.end)}`, `SUMMARY:Pinjam ${room(b.room).name}`, `DESCRIPTION:${b.reason.replace(/[\r\n,;]/g, ' ')}`, `LOCATION:${room(b.room).name}`, 'END:VEVENT'));
        L.push('END:VCALENDAR');
        const a = Object.assign(document.createElement('a'), { href: URL.createObjectURL(new Blob([L.join('\r\n')], { type: 'text/calendar' })), download: 'peminjaman-eroom.ics' });
        a.click(); URL.revokeObjectURL(a.href); toast('File kalender diunduh.');
    }

    /* ---------- Event ---------- */
    function shiftMonth(n) { const d = new Date(state.year, state.month + n, 1); state.year = d.getFullYear(); state.month = d.getMonth(); renderCalendar(); }
    function goToday() { state.year = TODAY.getFullYear(); state.month = TODAY.getMonth(); state.q.date = todayIso; syncInputs(); renderAll(); }
    $('#prev').onclick = () => shiftMonth(-1); $('#next').onclick = () => shiftMonth(1); $('#today').onclick = goToday;
    $('#btnNew').onclick = () => openModal();
    $('#btnClose').onclick = closeModal; $('#btnCancel').onclick = closeModal;
    $('#modal').addEventListener('mousedown', e => { if (e.target.id === 'modal') closeModal(); });

    const bindQ = (id, key, fn = v => v) => $(id).addEventListener('input', e => { state.q[key] = fn(e.target.type === 'checkbox' ? e.target.checked : e.target.value); if (key === 'date' && state.q.date) { const d = fromIso(state.q.date); state.year = d.getFullYear(); state.month = d.getMonth(); } renderAll(); });
    bindQ('#qDate', 'date'); bindQ('#qStart', 'start'); bindQ('#qEnd', 'end'); bindQ('#qKind', 'kind'); bindQ('#qCap', 'cap', v => Number(v) || 0); bindQ('#qFree', 'free');
    $('#search').oninput = e => { state.query = e.target.value; renderRooms(); renderAgenda(); };

    $('#grid').addEventListener('click', e => {
        const c = e.target.closest('[data-date]'); if (!c) return;
        state.q.date = c.dataset.date;
        if (state.q.date < todayIso) toast('Tanggal ini sudah lewat. Kamu hanya bisa melihat riwayatnya.');
        syncInputs(); renderAll(); $('#sectAvail').scrollIntoView({ behavior: 'smooth' });
    });
    $('#rooms').addEventListener('click', e => { const b = e.target.closest('[data-book]'); if (b) openModal({ room: b.dataset.book }); });
    document.addEventListener('click', e => { const d = e.target.closest('[data-del]'); if (d) removeBooking(d.dataset.del); });

    $$('.nav-btn').forEach(b => b.addEventListener('click', () => {
        const k = b.dataset.nav;
        if (k === 'avail') $('#sectAvail').scrollIntoView({ behavior: 'smooth' });
        if (k === 'cal') $('#sectCal').scrollIntoView({ behavior: 'smooth' });
        if (k === 'mine') $('#panelMine').scrollIntoView({ behavior: 'smooth' });
        if (k === 'new') openModal();
    }));

    /* Profil */
    $('#btnProfile').onclick = e => { e.stopPropagation(); $('#menuProfile').classList.toggle('hidden'); };
    document.addEventListener('click', e => { if (!e.target.closest('#menuProfile')) $('#menuProfile').classList.add('hidden'); });
    $('#btnExport').onclick = () => { exportIcs(); $('#menuProfile').classList.add('hidden'); };

    /* Tema */
    let theme = 'light';
    try { theme = localStorage.getItem(THEME_KEY) || 'light'; } catch {}
    const applyTheme = t => document.documentElement.classList.toggle('dark', t === 'dark');
    applyTheme(theme);
    $('#btnTheme').onclick = () => { theme = theme === 'dark' ? 'light' : 'dark'; applyTheme(theme); try { localStorage.setItem(THEME_KEY, theme); } catch {} };

    /* Warna sidebar */
    const SB_KEY = 'eroom.sidebar.v1';
    const SB_PRESETS = [{ name: 'Hijau Unmul', bg: '#06261a' }, { name: 'Biru malam', bg: '#0c1f3d' }, { name: 'Ungu', bg: '#2e1065' }, { name: 'Cokelat', bg: '#431407' }, { name: 'Hitam', bg: '#1c1917' }, { name: 'Putih', bg: '#ffffff' }];
    function sbColors(hex) {
        const n = parseInt(hex.slice(1), 16), r = n >> 16, g = (n >> 8) & 255, b = n & 255;
        return (0.299 * r + 0.587 * g + 0.114 * b) > 150
            ? { bg: hex, fg: '#1c1917', muted: '#57534e', hover: 'rgba(0,0,0,.07)' }
            : { bg: hex, fg: '#ffffff', muted: 'rgba(255,255,255,.7)', hover: 'rgba(255,255,255,.12)' };
    }
    function applySidebar(hex, persist) {
        const c = sbColors(hex), st = document.documentElement.style;
        st.setProperty('--sb-bg', c.bg); st.setProperty('--sb-fg', c.fg); st.setProperty('--sb-muted', c.muted); st.setProperty('--sb-hover', c.hover);
        $$('#sbSwatches [data-hex]').forEach(el => { const on = el.dataset.hex.toLowerCase() === hex.toLowerCase(); el.setAttribute('aria-checked', String(on)); el.style.boxShadow = on ? '0 0 0 2px var(--sb-bg), 0 0 0 4px #f25f0f' : ''; });
        if (persist) { try { localStorage.setItem(SB_KEY, hex); } catch {} }
    }
    $('#sbSwatches').innerHTML = SB_PRESETS.map(p => `<button type="button" role="radio" data-hex="${p.bg}" title="${p.name}" aria-label="${p.name}" class="h-7 w-7 rounded-full border border-stone-400/60" style="background:${p.bg}"></button>`).join('') +
        `<label class="relative flex h-7 cursor-pointer items-center rounded-full border border-stone-400/60 px-2 text-xs font-semibold" title="Pilih warna sendiri">Kustom<input id="sbCustom" type="color" value="#06261a" class="absolute inset-0 h-full w-full cursor-pointer opacity-0" aria-label="Warna sidebar kustom"></label>`;
    $('#sbSwatches').addEventListener('click', e => { const b = e.target.closest('[data-hex]'); if (b) applySidebar(b.dataset.hex, true); });
    $('#sbCustom').addEventListener('input', e => applySidebar(e.target.value, true));
    let sbSaved = '#06261a'; try { sbSaved = localStorage.getItem(SB_KEY) || sbSaved; } catch {}
    $('#sbCustom').value = sbSaved; applySidebar(sbSaved, false);

    /* Sidebar minimize */
    const COL_KEY = 'eroom.sidebar.collapsed';
    function setCollapsed(c, persist) {
        $('#sidebar').classList.toggle('collapsed', c);
        const t = $('#btnToggleSidebar'); t.setAttribute('aria-expanded', String(!c)); t.setAttribute('aria-label', c ? 'Besarkan sidebar' : 'Kecilkan sidebar');
        if (persist) { try { localStorage.setItem(COL_KEY, c ? '1' : '0'); } catch {} }
    }
    let startCol = window.innerWidth < 1024;
    try { const v = localStorage.getItem(COL_KEY); if (v !== null) startCol = v === '1'; } catch {}
    setCollapsed(startCol, false);
    $('#btnToggleSidebar').onclick = () => setCollapsed(!$('#sidebar').classList.contains('collapsed'), true);

    /* Mulai */
    $('#year').textContent = TODAY.getFullYear();
    load(); renderFilters(); syncInputs(); renderAll();
})();
</script>
@endverbatim
</body>
</html>