@extends('admin.layout')
@section('title', 'Mahasiswa')
@section('heading', 'Kelola akun mahasiswa')

@section('content')
<div class="flex flex-wrap items-end gap-3">
    <div class="mr-auto">
        <h2 class="text-lg font-extrabold">Akun mahasiswa</h2>
        <p class="text-sm text-stone-500">{{ $students->total() }} akun. Mahasiswa login memakai NIM dan kata sandi di sini.</p>
    </div>
    <form method="GET" class="flex gap-2">
        <input type="search" name="q" value="{{ $q }}" placeholder="Cari nama atau NIM" class="field mt-0 w-56" aria-label="Cari mahasiswa">
        <button class="btn btn-ghost">Cari</button>
    </form>
    <button type="button" class="btn btn-primary" data-open="dlgStudent" data-action="{{ route('admin.mahasiswa.store') }}" data-title="Tambah mahasiswa">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
        Tambah mahasiswa
    </button>
</div>

<div class="card overflow-x-auto">
    <table class="w-full min-w-[640px] text-left">
        <thead class="border-b border-stone-200 bg-stone-50 dark:border-stone-800 dark:bg-stone-800/50">
            <tr><th class="th">NIM</th><th class="th">Nama</th><th class="th">Program studi</th><th class="th">Peminjaman</th><th class="th">Aksi</th></tr>
        </thead>
        <tbody class="divide-y divide-stone-200 dark:divide-stone-800">
            @forelse ($students as $u)
                <tr>
                    <td class="td font-bold tabular-nums">{{ $u->nim }}</td>
                    <td class="td font-semibold">{{ $u->name }}</td>
                    <td class="td">{{ $u->prodi }}</td>
                    <td class="td">{{ $u->bookings_count }}</td>
                    <td class="td">
                        <div class="flex gap-1.5">
                            <button type="button" class="btn btn-ghost btn-sm" data-open="dlgStudent" data-title="Ubah mahasiswa" data-method="PUT"
                                    data-action="{{ route('admin.mahasiswa.update', $u) }}"
                                    data-item="{{ json_encode($u->only(['name', 'nim', 'prodi'])) }}">Ubah</button>
                            <form method="POST" action="{{ route('admin.mahasiswa.destroy', $u) }}">
                                @csrf @method('DELETE')
                                <button type="button" onclick="showAdminConfirmModal(this.form, 'Hapus Akun Mahasiswa', 'Apakah Anda yakin ingin menghapus akun mahasiswa {{ $u->name }} ({{ $u->nim }}) beserta seluruh riwayat peminjamannya?')" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="p-8 text-center text-sm text-stone-500">Belum ada akun mahasiswa{{ $q !== '' ? ' yang cocok dengan pencarian' : '' }}.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div>{{ $students->links() }}</div>

<dialog id="dlgStudent" class="m-auto w-full max-w-md rounded-2xl bg-white p-0 text-stone-800 shadow-2xl dark:bg-stone-900 dark:text-stone-100">
    <form method="POST" class="space-y-3 p-5" autocomplete="off">
        @csrf
        <input type="hidden" name="_method" value="POST">
        <div class="flex items-center justify-between">
            <h2 data-title class="text-lg font-extrabold">Tambah mahasiswa</h2>
            <button type="button" data-close class="rounded-lg p-2 hover:bg-stone-100 dark:hover:bg-stone-800" aria-label="Tutup"><svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6 6 18"/></svg></button>
        </div>

        <label class="block text-sm font-semibold">Nama lengkap
            <input name="name" required maxlength="60" class="field" placeholder="Sesuai KTM">
        </label>
        <label class="block text-sm font-semibold">NIM
            <input name="nim" required inputmode="numeric" pattern="\d{8,12}" maxlength="12" class="field" placeholder="Contoh: 2509106001">
        </label>
        <label class="block text-sm font-semibold">Program studi
            <input name="prodi" required list="prodiList" maxlength="40" class="field" placeholder="Pilih atau ketik">
            <datalist id="prodiList">
                @foreach (['Informatika', 'Teknik Sipil', 'Teknik Mesin', 'Teknik Elektro', 'Teknik Kimia', 'Teknik Industri', 'Teknik Perkapalan', 'Arsitektur', 'Teknik Lingkungan', 'Teknik Geologi'] as $p)<option value="{{ $p }}">@endforeach
            </datalist>
        </label>
        <label class="block text-sm font-semibold">Kata sandi
            <input type="password" name="password" data-req-create required minlength="8" maxlength="72" autocomplete="new-password" class="field" placeholder="Minimal 8 karakter">
            <span class="mt-1 block text-xs font-normal text-stone-500">Saat mengubah data, kosongkan jika kata sandi tidak ingin diganti.</span>
        </label>

        <div class="flex justify-end gap-2 pt-1">
            <button type="button" data-close class="btn btn-ghost">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</dialog>
@endsection