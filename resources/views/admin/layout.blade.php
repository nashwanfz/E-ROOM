<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Admin E-ROOM</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        try {
            const t = localStorage.getItem('eroom.theme') || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
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
    $nav = [
        ['admin.dashboard', 'admin.dashboard', 'Ringkasan', 'M3 12l9-9 9 9M5 10v10h14V10'],
        ['admin.peminjaman.index', 'admin.peminjaman.*', 'Peminjaman', 'M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z'],
        ['admin.ruangan.index', 'admin.ruangan.*', 'Ruangan', 'M3 21h18M5 21V7l7-4 7 4v14M9 9h1m4 0h1M9 13h1m4 0h1M9 17h1m4 0h1'],
        ['admin.mahasiswa.index', 'admin.mahasiswa.*', 'Mahasiswa', 'M17 21v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75'],
    ];
@endphp

<div class="flex min-h-screen">

    {{-- ============ SIDEBAR (desktop) ============ --}}
    <aside class="sticky top-0 hidden h-screen w-64 shrink-0 flex-col bg-unmul-950 text-unmul-50 lg:flex">
        <div class="flex items-center gap-3 px-5 pb-4 pt-6">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white p-1.5">
                <img src="{{ asset('image/unmul2.png') }}" alt="Logo Unmul" class="h-full w-full object-contain"
                     onerror="this.replaceWith(Object.assign(document.createElement('span'),{className:'text-sm font-extrabold text-unmul-700',textContent:'FT'}))">
            </div>
            <div class="leading-tight">
                <p class="text-lg font-extrabold tracking-tight">E-ROOM</p>
                <p class="text-xs text-unmul-200">Panel admin</p>
            </div>
        </div>

        <div class="mx-5 mb-3 rounded-xl bg-white/10 px-3 py-2 text-xs text-unmul-200">
            Masuk sebagai <span class="font-semibold text-white">{{ auth()->user()->name }}</span>
        </div>

        <nav class="flex-1 space-y-1 px-3" aria-label="Menu admin">
            @foreach ($nav as [$route, $pattern, $label, $icon])
                <a href="{{ route($route) }}" @if (request()->routeIs($pattern)) aria-current="page" @endif
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm {{ request()->routeIs($pattern) ? 'bg-white/15 font-bold' : 'hover:bg-white/10' }}">
                    <svg class="h-5 w-5 shrink-0 text-unmul-200" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="{{ $icon }}"/></svg>
                    {{ $label }}
                </a>
            @endforeach
        </nav>

        <form method="POST" action="{{ route('logout') }}" class="border-t border-white/10 p-3">
            @csrf
            <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm hover:bg-white/10">
                <svg class="h-5 w-5 shrink-0 text-unmul-200" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
                Keluar
            </button>
        </form>
    </aside>

    {{-- ============ MAIN ============ --}}
    <div class="flex min-w-0 flex-1 flex-col">
        <header class="sticky top-0 z-20 flex items-center gap-3 border-b border-stone-200 bg-white/85 px-4 py-3 backdrop-blur dark:border-stone-800 dark:bg-stone-900/85 sm:px-6">
            <h1 class="text-base font-extrabold tracking-tight sm:text-xl">@yield('heading')</h1>
            <button id="btnTheme" class="ml-auto rounded-lg p-2 hover:bg-stone-100 dark:hover:bg-stone-800" aria-label="Ganti tema gelap atau terang">
                <svg class="h-5 w-5 dark:hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12.8A9 9 0 1111.2 3a7 7 0 009.8 9.8z"/></svg>
                <svg class="hidden h-5 w-5 dark:block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M4.9 4.9l1.4 1.4m11.4 11.4 1.4 1.4M2 12h2m16 0h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
            </button>
            <form method="POST" action="{{ route('logout') }}" class="lg:hidden">
                @csrf
                <button type="submit" class="btn btn-ghost btn-sm">Keluar</button>
            </form>
        </header>

        {{-- Menu untuk layar kecil --}}
        <nav class="flex gap-1 overflow-x-auto border-b border-stone-200 bg-white px-3 py-2 dark:border-stone-800 dark:bg-stone-900 lg:hidden" aria-label="Menu admin">
            @foreach ($nav as [$route, $pattern, $label, $icon])
                <a href="{{ route($route) }}" class="shrink-0 rounded-lg px-3 py-1.5 text-sm {{ request()->routeIs($pattern) ? 'bg-unmul-600 font-bold text-white' : 'hover:bg-stone-100 dark:hover:bg-stone-800' }}">{{ $label }}</a>
            @endforeach
        </nav>

        <main class="mx-auto w-full max-w-[1400px] flex-1 space-y-5 p-4 sm:p-6">
            @if (session('status'))
                <div class="rounded-lg border border-unmul-200 bg-unmul-50 px-4 py-3 text-sm font-medium text-unmul-800 dark:border-unmul-800 dark:bg-unmul-950/60 dark:text-unmul-200" role="status">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300" role="alert">
                    <p class="font-bold">Perubahan belum tersimpan:</p>
                    <ul class="mt-1 list-inside list-disc">
                        @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="border-t border-stone-200 px-6 py-4 text-center text-xs text-stone-500 dark:border-stone-800">
            &copy; {{ date('Y') }} Fakultas Teknik, Universitas Mulawarman
        </footer>
    </div>
</div>

@verbatim
<script>
(() => {
    // Modal (dialog): buka untuk tambah / ubah, isi form dari data-item
    document.addEventListener('click', e => {
        const open = e.target.closest('[data-open]');
        if (open) {
            const dlg = document.getElementById(open.dataset.open);
            const form = dlg.querySelector('form');
            const item = open.dataset.item ? JSON.parse(open.dataset.item) : null;
            form.reset();
            form.action = open.dataset.action;
            form.elements._method.value = open.dataset.method || 'POST';
            const title = dlg.querySelector('[data-title]');
            if (title) title.textContent = open.dataset.title || '';
            form.querySelectorAll('[data-req-create]').forEach(el => { el.required = !item; });
            if (item) {
                for (const [k, v] of Object.entries(item)) {
                    const el = form.elements[k];
                    if (!el) continue;
                    if (el.type === 'checkbox') el.checked = !!v; else el.value = v ?? '';
                }
            }
            dlg.showModal();
            return;
        }
        const close = e.target.closest('[data-close]');
        if (close) { close.closest('dialog').close(); return; }
        // klik area gelap di luar kartu modal = tutup
        if (e.target.tagName === 'DIALOG') e.target.close();
    });

    // Tema
    document.getElementById('btnTheme').onclick = () => {
        const dark = !document.documentElement.classList.contains('dark');
        document.documentElement.classList.toggle('dark', dark);
        try { localStorage.setItem('eroom.theme', dark ? 'dark' : 'light'); } catch (e) {}
    };
})();
</script>
@endverbatim
</body>
</html>