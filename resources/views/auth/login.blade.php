
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk - E-ROOM Fakultas Teknik</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif']
                    },
                    colors: {
                        unmul: {
                            50: '#effaf3',
                            100: '#d8f3e1',
                            200: '#b3e6c7',
                            300: '#80d3a4',
                            400: '#4ab97d',
                            500: '#27a062',
                            600: '#188150',
                            700: '#146842',
                            800: '#135336',
                            900: '#0f4430',
                            950: '#06261a'
                        },
                        ember: {
                            50: '#fff4ec',
                            100: '#ffe6d2',
                            200: '#ffc9a1',
                            300: '#ffa564',
                            400: '#fb7d2e',
                            500: '#f25f0f',
                            600: '#d9480a',
                            700: '#b4350b'
                        }
                    }
                }
            }
        };
    </script>

    @verbatim
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        }

        .field {
            width: 100%;
            border-radius: .6rem;
            border: 1px solid #d6d3d1;
            background: #fff;
            padding: .65rem .85rem;
            outline: none;
        }

        .field:focus {
            border-color: #fb7d2e;
            box-shadow: 0 0 0 3px rgba(251,125,46,.25);
        }

        .field.invalid {
            border-color: #dc2626;
        }

        .dark .field {
            background: #292524;
            border-color: #44403c;
            color: #f5f5f4;
        }

        .bar {
            transform-origin: left;
            animation: fill .9s cubic-bezier(.2,.7,.2,1) both;
        }

        @keyframes fill {
            from {
                transform: scaleX(0);
            }
            to {
                transform: scaleX(1);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .bar {
                animation: none;
            }
        }
    </style>
    @endverbatim
</head>

<body class="bg-stone-100 text-stone-800 antialiased dark:bg-stone-950 dark:text-stone-100">

<div class="grid min-h-screen lg:grid-cols-[minmax(0,1.05fr)_minmax(0,1fr)]">

    {{-- ===== PANEL KIRI ===== --}}

    <aside
        class="relative hidden flex-col justify-between overflow-hidden bg-cover bg-center bg-no-repeat p-10 text-unmul-50 lg:flex"
        style="background-image: url('{{ asset('images/kelas.png') }}');"
        aria-hidden="true"
    >

        {{-- Overlay hijau --}}
        <div class="absolute inset-0 bg-unmul-950/65"></div>

        {{-- LOGO DAN IDENTITAS E-ROOM --}}
        <div class="relative z-10 flex items-center gap-4">

            {{-- Logo Unmul diperbesar --}}
            <div class="flex h-20 w-20 shrink-0 items-center justify-center">
                <img
                    src="{{ asset('images/logo-unmul.webp') }}"
                    alt=""
                    class="h-full w-full object-contain"
                    onerror="this.replaceWith(Object.assign(document.createElement('span'),{className:'text-lg font-extrabold text-white',textContent:'FT'}))"
                >
            </div>

            {{-- Tulisan E-ROOM diperbesar --}}
            <div class="leading-tight">
                <p class="text-2xl font-extrabold tracking-tight">
                    E-ROOM
                </p>

                <p class="mt-1 text-sm font-medium text-unmul-200">
                    Fakultas Teknik Unmul
                </p>
            </div>

        </div>


        {{-- ILUSTRASI JADWAL RUANGAN --}}
        <div class="relative z-10">

            <h2 class="max-w-md text-4xl font-extrabold leading-tight tracking-tight">
                Cek ruangan kosong sebelum jalan ke kampus.
            </h2>

            @verbatim
            <div class="mt-10 max-w-md space-y-4 text-xs text-unmul-200">

                <div>
                    <div class="mb-1.5 flex justify-between">
                        <span class="font-semibold text-unmul-50">Ruang A.101</span>
                        <span>Tersedia pukul 09.30</span>
                    </div>

                    <div class="relative h-3 rounded bg-white/10">
                        <span class="bar absolute inset-y-0 rounded-sm bg-red-500"
                              style="left:4%;width:15%">
                        </span>

                        <span class="bar absolute inset-y-0 rounded-sm bg-red-500"
                              style="left:46%;width:15%;animation-delay:.1s">
                        </span>
                    </div>
                </div>

                <div>
                    <div class="mb-1.5 flex justify-between">
                        <span class="font-semibold text-unmul-50">Lab Komputer 1</span>
                        <span>Kosong sore ini</span>
                    </div>

                    <div class="relative h-3 rounded bg-white/10">
                        <span class="bar absolute inset-y-0 rounded-sm bg-red-500"
                              style="left:15%;width:23%;animation-delay:.2s">
                        </span>

                        <span class="bar absolute inset-y-0 rounded-sm bg-ember-500"
                              style="left:62%;width:15%;animation-delay:.3s">
                        </span>
                    </div>
                </div>

                <div>
                    <div class="mb-1.5 flex justify-between">
                        <span class="font-semibold text-unmul-50">Ruang Seminar TA 1</span>
                        <span>Kosong sepanjang hari</span>
                    </div>

                    <div class="relative h-3 rounded bg-white/10"></div>
                </div>

                <div class="flex justify-between pt-1 text-[10px] text-unmul-300">
                    <span>07</span>
                    <span>10</span>
                    <span>13</span>
                    <span>16</span>
                    <span>20</span>
                </div>

            </div>
            @endverbatim

        </div>


        {{-- COPYRIGHT --}}
        <p class="relative z-10 text-xs text-unmul-300">
            &copy; {{ date('Y') }} Fakultas Teknik, Universitas Mulawarman
        </p>

    </aside>


    {{-- ===== PANEL KANAN: FORM LOGIN ===== --}}

    <main class="flex items-center justify-center p-5 sm:p-10">

        <div class="w-full max-w-md">

            {{-- IDENTITAS UNTUK MOBILE --}}
            <div class="mb-8 flex items-center gap-3 lg:hidden">

                <div class="flex h-[72px] w-[72px] shrink-0 items-center justify-center">
                    <img
                        src="{{ asset('images/logo-unmul.webp') }}"
                        alt="Logo Unmul"
                        class="h-full w-full object-contain"
                        onerror="this.replaceWith(Object.assign(document.createElement('span'),{className:'text-lg font-extrabold text-unmul-700',textContent:'FT'}))"
                    >
                </div>

                <div class="leading-tight">
                    <p class="text-xl font-extrabold tracking-tight">
                        E-ROOM
                    </p>

                    <p class="mt-1 text-sm text-stone-500">
                        Fakultas Teknik Unmul
                    </p>
                </div>

            </div>


            {{-- JUDUL LOGIN --}}
            <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl">
                Masuk ke E-ROOM
            </h1>

            <p class="mt-1.5 text-sm text-stone-500 dark:text-stone-400">
                Gunakan NIM dan kata sandi mahasiswa kamu.
            </p>


            {{-- NOTIFIKASI STATUS --}}
            @if (session('status'))
                <div
                    class="mt-5 rounded-lg border border-unmul-200 bg-unmul-50 px-4 py-3 text-sm font-medium text-unmul-800 dark:border-unmul-800 dark:bg-unmul-950/60 dark:text-unmul-200"
                    role="status"
                >
                    {{ session('status') }}
                </div>
            @endif


            {{-- PESAN ERROR --}}
            @if ($errors->any())
                <div
                    class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300"
                    role="alert"
                >
                    {{ $errors->first() }}
                </div>
            @endif


            {{-- FORM LOGIN --}}
            <form
                method="POST"
                action="{{ route('login.attempt') }}"
                class="mt-6 space-y-4"
                novalidate
            >

                @csrf

                {{-- NIM --}}
                <label class="block text-sm font-semibold">
                    NIM

                    <input
                        name="nim"
                        id="nim"
                        type="text"
                        inputmode="numeric"
                        autocomplete="username"
                        autofocus
                        required
                        maxlength="12"
                        value="{{ old('nim') }}"
                        placeholder="Contoh: 2509106001"
                        class="field mt-1 font-normal @error('nim') invalid @enderror"
                        @error('nim') aria-invalid="true" @enderror
                    >
                </label>


                {{-- PASSWORD --}}
                <label class="block text-sm font-semibold">
                    Kata sandi

                    <span class="relative mt-1 block">

                        <input
                            name="password"
                            id="password"
                            type="password"
                            autocomplete="current-password"
                            required
                            class="field pr-20 font-normal @error('password') invalid @enderror"
                        >

                        <button
                            type="button"
                            id="togglePw"
                            class="absolute right-2 top-1/2 -translate-y-1/2 rounded-md px-2 py-1 text-xs font-bold text-stone-500 hover:bg-stone-100 dark:hover:bg-stone-800"
                            aria-controls="password"
                            aria-pressed="false"
                        >
                            Tampilkan
                        </button>

                    </span>
                </label>


                {{-- INGAT SAYA --}}
                <div class="flex items-center justify-between text-sm">

                    <label class="inline-flex items-center gap-2">
                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                            class="h-4 w-4 accent-[#f25f0f]"
                            @checked(old('remember'))
                        >

                        Ingat saya di perangkat ini
                    </label>

                    <span class="text-xs text-stone-500">
                        Lupa kata sandi? Hubungi admin jurusan.
                    </span>

                </div>


                {{-- TOMBOL MASUK --}}
                <button
                    type="submit"
                    id="btnSubmit"
                    class="w-full rounded-lg bg-ember-500 px-5 py-3 text-sm font-bold text-white shadow hover:bg-ember-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-ember-300 disabled:opacity-60"
                >
                    Masuk
                </button>

            </form>


            {{-- FOOTER --}}
            <p class="mt-8 text-center text-xs text-stone-500">
                Hanya untuk mahasiswa Fakultas Teknik Universitas Mulawarman.
            </p>

        </div>

    </main>

</div>


{{-- ===== JAVASCRIPT ===== --}}

@verbatim
<script>
    (() => {

        // Toggle password
        const pw = document.getElementById('password');
        const t = document.getElementById('togglePw');

        t.addEventListener('click', () => {

            const show = pw.type === 'password';

            pw.type = show ? 'text' : 'password';

            t.textContent = show ? 'Sembunyikan' : 'Tampilkan';

            t.setAttribute('aria-pressed', String(show));

        });


        // Membatasi input NIM hanya angka
        const nim = document.getElementById('nim');

        nim.addEventListener('input', () => {
            nim.value = nim.value.replace(/\D/g, '');
        });


        // Status tombol saat form dikirim
        document.querySelector('form').addEventListener('submit', () => {

            const b = document.getElementById('btnSubmit');

            b.disabled = true;

            b.textContent = 'Memeriksa…';

        });


        // Tema mengikuti pilihan yang tersimpan
        try {

            const th = localStorage.getItem('eroom.theme') ||
                (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

            document.documentElement.classList.toggle('dark', th === 'dark');

        } catch {}

    })();
</script>
@endverbatim

</body>
</html>
