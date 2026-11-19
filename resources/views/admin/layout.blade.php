<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Panel Pengelola E-ROOM Fakultas Teknik Universitas Mulawarman</title>

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
    $isSuperAdmin = auth()->user()->role === 'super_admin';
    $nav = [
        ['admin.dashboard', 'admin.dashboard', 'Ringkasan', 'M3 12l9-9 9 9M5 10v10h14V10'],
        ['admin.peminjaman.index', 'admin.peminjaman.*', 'Peminjaman', 'M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z'],
        ['admin.ruangan.index', 'admin.ruangan.*', 'Ruangan', 'M3 21h18M5 21V7l7-4 7 4v14M9 9h1m4 0h1M9 13h1m4 0h1M9 17h1m4 0h1'],
        ['admin.mahasiswa.index', 'admin.mahasiswa.*', 'Mahasiswa', 'M17 21v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75'],
        ['admin.laporan.index', 'admin.laporan.*', 'Laporan', 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
    ];

    if ($isSuperAdmin) {
        $nav[] = ['admin.pengelola.index', 'admin.pengelola.*', 'Kelola Admin', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'];
    }
@endphp

<div class="flex min-h-screen">

    {{-- ============ SIDEBAR ADMIN (desktop) ============ --}}
    <aside class="sticky top-0 hidden h-screen w-64 shrink-0 flex-col bg-unmul-950 text-unmul-50 lg:flex">
        <div class="flex items-center gap-3 px-5 pb-4 pt-6">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white p-1.5">
                <img src="{{ asset('images/logo-unmul.webp') }}" alt="Logo Unmul" class="h-full w-full object-contain"
                     onerror="this.replaceWith(Object.assign(document.createElement('span'),{className:'text-sm font-extrabold text-unmul-700',textContent:'FT'}))">
            </div>
            <div class="leading-tight">
                <p class="text-lg font-extrabold tracking-tight">E-ROOM</p>
                <p class="text-xs text-unmul-200">{{ $isSuperAdmin ? 'Panel Super Admin' : 'Panel Admin' }} &bull; Fakultas Teknik Universitas Mulawarman</p>
            </div>
        </div>

        <div class="mx-5 mb-3 rounded-xl bg-white/10 px-3 py-2 text-xs text-unmul-200">
            Masuk sebagai <span class="font-semibold text-white">{{ auth()->user()->name }}</span>
            <p class="text-[11px] text-unmul-300 capitalize">Peran: {{ str_replace('_', ' ', auth()->user()->role) }}</p>
        </div>

        <nav class="flex-1 space-y-1 px-3" aria-label="Menu pengelola">
            <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-wider text-unmul-300">Navigasi Utama</p>
            @foreach ($nav as [$route, $pattern, $label, $icon])
                <a href="{{ route($route) }}" @if (request()->routeIs($pattern)) aria-current="page" @endif
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors {{ request()->routeIs($pattern) ? 'bg-white/20 font-bold text-white shadow-sm' : 'hover:bg-white/10 text-unmul-100' }}">
                    <svg class="h-5 w-5 shrink-0 text-unmul-200" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="{{ $icon }}"/></svg>
                    {{ $label }}
                </a>
            @endforeach
        </nav>

        <div class="p-4 text-center text-xs text-unmul-300 border-t border-white/10">
            &copy; {{ date('Y') }} Fakultas Teknik Universitas Mulawarman
        </div>
    </aside>

    {{-- ============ MAIN AREA ============ --}}
    <div class="flex min-w-0 flex-1 flex-col">
        <header class="sticky top-0 z-20 flex items-center gap-3 border-b border-stone-200 bg-white/85 px-4 py-3 backdrop-blur dark:border-stone-800 dark:bg-stone-900/85 sm:px-6">
            <h1 class="text-base font-extrabold tracking-tight sm:text-xl">@yield('heading')</h1>

            <button id="btnTheme" class="ml-auto rounded-lg p-2 hover:bg-stone-100 dark:hover:bg-stone-800" aria-label="Ganti tema gelap atau terang">
                <svg class="h-5 w-5 dark:hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12.8A9 9 0 1111.2 3a7 7 0 009.8 9.8z"/></svg>
                <svg class="hidden h-5 w-5 dark:block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M4.9 4.9l1.4 1.4m11.4 11.4 1.4 1.4M2 12h2m16 0h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
            </button>

            {{-- Profile Dropdown with Logout (Task 10) --}}
            <div class="relative">
                <button id="btnAdminProfile" class="flex items-center gap-2 rounded-xl px-2 py-1.5 hover:bg-stone-100 dark:hover:bg-stone-800" aria-haspopup="true">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-unmul-600 text-sm font-bold text-white">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                    <span class="hidden text-sm font-semibold sm:block">{{ auth()->user()->name }}</span>
                    <svg class="h-4 w-4 text-stone-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div id="menuAdminProfile" class="absolute right-0 mt-2 hidden w-64 rounded-xl border border-stone-200 bg-white p-2 text-sm shadow-xl dark:border-stone-700 dark:bg-stone-900 z-50">
                    <div class="border-b border-stone-100 px-3 py-2 dark:border-stone-800">
                        <p class="font-bold text-stone-900 dark:text-stone-100">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-stone-500">ID: {{ auth()->user()->nim }}</p>
                        <p class="text-xs text-stone-500 capitalize">Peran: {{ str_replace('_', ' ', auth()->user()->role) }}</p>
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

        {{-- Mobile navigation --}}
        <nav class="flex gap-1 overflow-x-auto border-b border-stone-200 bg-white px-3 py-2 dark:border-stone-800 dark:bg-stone-900 lg:hidden" aria-label="Menu pengelola mobile">
            @foreach ($nav as [$route, $pattern, $label, $icon])
                <a href="{{ route($route) }}" class="shrink-0 rounded-lg px-3 py-1.5 text-xs font-semibold {{ request()->routeIs($pattern) ? 'bg-unmul-600 font-bold text-white' : 'hover:bg-stone-100 dark:hover:bg-stone-800' }}">
                    {{ $label }}
                </a>
            @endforeach
        </nav>

        <main class="mx-auto w-full max-w-[1400px] flex-1 space-y-5 p-4 sm:p-6">
            @yield('content')
        </main>

        <footer class="border-t border-stone-200 px-6 py-4 text-center text-xs text-stone-500 dark:border-stone-800">
            &copy; {{ date('Y') }} Fakultas Teknik Universitas Mulawarman
        </footer>
    </div>
</div>

{{-- ===================== GLOBAL MODAL KONFIRMASI (Task 4) ===================== --}}
<dialog id="dlgAdminConfirm" class="m-auto w-full max-w-md rounded-2xl bg-white p-0 text-stone-800 shadow-2xl dark:bg-stone-900 dark:text-stone-100">
    <div class="p-6 text-center">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-amber-100 text-amber-600 dark:bg-amber-900/40 dark:text-amber-300">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h3 id="adminConfirmTitle" class="mt-4 text-lg font-bold">Konfirmasi Tindakan</h3>
        <p id="adminConfirmMessage" class="mt-2 text-sm text-stone-600 dark:text-stone-400">Apakah Anda yakin ingin melanjutkan tindakan ini?</p>
        <div class="mt-6 flex justify-center gap-3">
            <button type="button" onclick="document.getElementById('dlgAdminConfirm').close()" class="btn btn-ghost">Batal</button>
            <button type="button" id="btnAdminConfirmAction" class="btn btn-primary">Ya, Lanjutkan</button>
        </div>
    </div>
</dialog>

{{-- ===================== GLOBAL MODAL TOLAK DENGAN ALASAN (Task 4) ===================== --}}
<dialog id="dlgAdminReject" class="m-auto w-full max-w-md rounded-2xl bg-white p-0 text-stone-800 shadow-2xl dark:bg-stone-900 dark:text-stone-100">
    <form id="formAdminReject" method="POST" class="p-6 space-y-4">
        @csrf @method('PATCH')
        <input type="hidden" name="status" value="ditolak">
        <div class="flex items-center justify-between border-b border-stone-100 pb-2 dark:border-stone-800">
            <h3 class="text-base font-extrabold text-red-600">Tolak Peminjaman Ruangan</h3>
            <button type="button" onclick="document.getElementById('dlgAdminReject').close()" class="rounded-lg p-1 hover:bg-stone-100 dark:hover:bg-stone-800">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6 6 18"/></svg>
            </button>
        </div>
        <div>
            <label class="block text-sm font-semibold">Alasan Penolakan <span class="text-stone-400 font-normal">(opsional)</span>:
                <textarea name="catatan_admin" rows="3" maxlength="200" class="field mt-1" placeholder="Contoh: Ruangan sedang direnovasi atau diprioritaskan untuk acara fakultas."></textarea>
            </label>
        </div>
        <div class="flex justify-end gap-2 pt-2 border-t border-stone-100 dark:border-stone-800">
            <button type="button" onclick="document.getElementById('dlgAdminReject').close()" class="btn btn-ghost">Batal</button>
            <button type="submit" class="btn btn-danger">Tolak Peminjaman</button>
        </div>
    </form>
</dialog>

{{-- ===================== GLOBAL MODAL PESAN & VALIDASI (Task 4) ===================== --}}
<dialog id="dlgAdminMessage" class="m-auto w-full max-w-md rounded-2xl bg-white p-0 text-stone-800 shadow-2xl dark:bg-stone-900 dark:text-stone-100">
    <div class="p-6 text-center">
        <div id="dlgAdminIconStatus" class="mx-auto flex h-12 w-12 items-center justify-center rounded-full"></div>
        <h3 id="dlgAdminTitleStatus" class="mt-4 text-lg font-bold">Pemberitahuan</h3>
        <div id="dlgAdminBodyStatus" class="mt-2 text-sm text-stone-600 dark:text-stone-400"></div>
        <div class="mt-6 flex justify-center">
            <button type="button" onclick="document.getElementById('dlgAdminMessage').close()" class="btn btn-ghost px-6">Tutup</button>
        </div>
    </div>
</dialog>

@verbatim
<script>
(() => {
    // Dropdown Profile
    const btnAdminProfile = document.getElementById('btnAdminProfile');
    const menuAdminProfile = document.getElementById('menuAdminProfile');
    if (btnAdminProfile && menuAdminProfile) {
        btnAdminProfile.addEventListener('click', (e) => {
            e.stopPropagation();
            menuAdminProfile.classList.toggle('hidden');
        });
        document.addEventListener('click', (e) => {
            if (!menuAdminProfile.contains(e.target) && !btnAdminProfile.contains(e.target)) {
                menuAdminProfile.classList.add('hidden');
            }
        });
    }

    // Modal data-open & data-close
    document.addEventListener('click', e => {
        const open = e.target.closest('[data-open]');
        if (open) {
            const dlg = document.getElementById(open.dataset.open);
            if (!dlg) return;
            const form = dlg.querySelector('form');
            if (form) {
                const item = open.dataset.item ? JSON.parse(open.dataset.item) : null;
                form.reset();
                form.action = open.dataset.action;
                if (form.elements._method) {
                    form.elements._method.value = open.dataset.method || 'POST';
                }
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
            }
            dlg.showModal();
            return;
        }
        const close = e.target.closest('[data-close]');
        if (close) { close.closest('dialog').close(); return; }
        if (e.target.tagName === 'DIALOG') e.target.close();
    });

    // Tema
    const btnTheme = document.getElementById('btnTheme');
    if (btnTheme) {
        btnTheme.onclick = () => {
            const dark = !document.documentElement.classList.contains('dark');
            document.documentElement.classList.toggle('dark', dark);
            try { localStorage.setItem('eroom.theme', dark ? 'dark' : 'light'); } catch (e) {}
        };
    }
})();

// Confirmation Modal Pop-up (Task 4)
let activeAdminConfirmForm = null;
function showAdminConfirmModal(formElement, title, message) {
    activeAdminConfirmForm = formElement;
    document.getElementById('adminConfirmTitle').textContent = title || 'Konfirmasi';
    document.getElementById('adminConfirmMessage').textContent = message || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
    document.getElementById('dlgAdminConfirm').showModal();
}
document.getElementById('btnAdminConfirmAction').addEventListener('click', () => {
    if (activeAdminConfirmForm) {
        activeAdminConfirmForm.submit();
    }
    document.getElementById('dlgAdminConfirm').close();
});

// Modal penolakan dengan catatan
function openRejectModal(actionUrl) {
    const form = document.getElementById('formAdminReject');
    form.action = actionUrl;
    form.reset();
    document.getElementById('dlgAdminReject').showModal();
}

// Show validation / status messages in modal pop-up (Task 4)
function showAdminModalMessage(type, title, messageHtml) {
    const iconContainer = document.getElementById('dlgAdminIconStatus');
    const titleEl = document.getElementById('dlgAdminTitleStatus');
    const bodyEl = document.getElementById('dlgAdminBodyStatus');

    if (type === 'error') {
        iconContainer.className = 'mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-300';
        iconContainer.innerHTML = '<svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
    } else {
        iconContainer.className = 'mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-green-600 dark:bg-green-900/40 dark:text-green-300';
        iconContainer.innerHTML = '<svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m4.5 12.75 6 6 9-13.5"/></svg>';
    }

    titleEl.textContent = title;
    bodyEl.innerHTML = messageHtml;
    document.getElementById('dlgAdminMessage').showModal();
}
</script>
@endverbatim

@if (session('status'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            showAdminModalMessage('success', 'Berhasil', {!! json_encode(session('status')) !!});
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
            showAdminModalMessage('error', 'Validasi / Perhatian', list);
        });
    </script>
@endif

</body>
</html>
