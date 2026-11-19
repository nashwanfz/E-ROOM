@extends('admin.layout')
@section('title', 'Kelola Admin')
@section('heading', 'Kelola Akun Admin Pengelola')

@section('content')
<div class="flex flex-wrap items-end gap-3">
    <div class="mr-auto">
        <h2 class="text-lg font-extrabold">Daftar Admin Pengelola</h2>
        <p class="text-sm text-stone-500">{{ $admins->total() }} akun admin pengelola terdaftar di sistem.</p>
    </div>
    <form method="GET" class="flex gap-2">
        <input type="search" name="q" value="{{ $q }}" placeholder="Cari nama atau NIP/NIM" class="field mt-0 w-56" aria-label="Cari admin">
        <button class="btn btn-ghost">Cari</button>
    </form>
    <button type="button" class="btn btn-primary" data-open="dlgAdminUser" data-action="{{ route('admin.pengelola.store') }}" data-title="Tambah Admin Pengelola">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
        Tambah Admin
    </button>
</div>

<div class="card overflow-x-auto">
    <table class="w-full min-w-[640px] text-left">
        <thead class="border-b border-stone-200 bg-stone-50 dark:border-stone-800 dark:bg-stone-800/50">
            <tr>
                <th class="th">NIP / NIM</th>
                <th class="th">Nama Lengkap</th>
                <th class="th">Unit Kerja / Prodi</th>
                <th class="th">Peran (Role)</th>
                <th class="th">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-200 dark:divide-stone-800">
            @forelse ($admins as $u)
                <tr>
                    <td class="td font-bold tabular-nums">{{ $u->nim }}</td>
                    <td class="td font-semibold">{{ $u->name }}</td>
                    <td class="td">{{ $u->prodi }}</td>
                    <td class="td">
                        <span class="inline-block rounded-full bg-blue-100 px-2.5 py-1 text-xs font-bold text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">Admin</span>
                    </td>
                    <td class="td">
                        <div class="flex gap-1.5">
                            <button type="button" class="btn btn-ghost btn-sm" data-open="dlgAdminUser" data-title="Ubah Admin Pengelola" data-method="PUT"
                                    data-action="{{ route('admin.pengelola.update', $u) }}"
                                    data-item="{{ json_encode($u->only(['name', 'nim', 'prodi'])) }}">Ubah</button>
                            <form method="POST" action="{{ route('admin.pengelola.destroy', $u) }}">
                                @csrf @method('DELETE')
                                <button type="button" onclick="showAdminConfirmModal(this.form, 'Hapus Akun Admin', 'Apakah Anda yakin ingin menghapus akun admin {{ $u->name }}?')" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="p-8 text-center text-sm text-stone-500">Belum ada akun admin yang terdaftar.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div>{{ $admins->links() }}</div>

{{-- Modal Tambah / Edit Admin (Task 3) --}}
<dialog id="dlgAdminUser" class="m-auto w-full max-w-md rounded-2xl bg-white p-0 text-stone-800 shadow-2xl dark:bg-stone-900 dark:text-stone-100">
    <form method="POST" class="space-y-3 p-5" autocomplete="off">
        @csrf
        <input type="hidden" name="_method" value="POST">
        <div class="flex items-center justify-between">
            <h2 data-title class="text-lg font-extrabold">Tambah Admin Pengelola</h2>
            <button type="button" data-close class="rounded-lg p-2 hover:bg-stone-100 dark:hover:bg-stone-800" aria-label="Tutup"><svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6 6 18"/></svg></button>
        </div>

        <label class="block text-sm font-semibold">Nama lengkap
            <input name="name" required maxlength="60" class="field" placeholder="Nama admin">
        </label>
        <label class="block text-sm font-semibold">NIP / NIM / ID Pengenal
            <input name="nim" required inputmode="numeric" pattern="\d{8,12}" maxlength="12" class="field" placeholder="Contoh: 1000000001">
        </label>
        <label class="block text-sm font-semibold">Unit kerja / Fakultas
            <input name="prodi" required maxlength="60" class="field" placeholder="Fakultas Teknik Universitas Mulawarman">
        </label>
        <label class="block text-sm font-semibold">Kata sandi
            <input type="password" name="password" data-req-create required minlength="8" maxlength="72" autocomplete="new-password" class="field" placeholder="Minimal 8 karakter">
            <span class="mt-1 block text-xs font-normal text-stone-500">Kosongkan jika kata sandi tidak ingin diubah.</span>
        </label>

        <div class="flex justify-end gap-2 pt-1">
            <button type="button" data-close class="btn btn-ghost">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</dialog>
@endsection
