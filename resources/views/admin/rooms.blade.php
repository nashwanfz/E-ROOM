@extends('admin.layout')
@section('title', 'Ruangan')
@section('heading', 'Kelola ruangan')

@section('content')
<div class="flex flex-wrap items-end gap-3">
    <div class="mr-auto">
        <h2 class="text-lg font-extrabold">Daftar ruangan</h2>
        <p class="text-sm text-stone-500">Ruangan yang dinonaktifkan tidak muncul di pilihan peminjaman.</p>
    </div>
    <button type="button" class="btn btn-primary" data-open="dlgRoom" data-action="{{ route('admin.ruangan.store') }}" data-title="Tambah ruangan">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
        Tambah ruangan
    </button>
</div>

<div class="card overflow-x-auto">
    <table class="w-full min-w-[760px] text-left">
        <thead class="border-b border-stone-200 bg-stone-50 dark:border-stone-800 dark:bg-stone-800/50">
            <tr><th class="th">Kode</th><th class="th">Nama</th><th class="th">Jenis</th><th class="th">Kapasitas</th><th class="th">Fasilitas</th><th class="th">Status</th><th class="th">Aksi</th></tr>
        </thead>
        <tbody class="divide-y divide-stone-200 dark:divide-stone-800">
            @forelse ($rooms as $room)
                <tr>
                    <td class="td font-bold">{{ $room->kode }}</td>
                    <td class="td font-semibold">{{ $room->nama }}<p class="text-xs font-normal text-stone-500">{{ $room->bookings_count }} peminjaman</p></td>
                    <td class="td">{{ \App\Models\Room::JENIS[$room->jenis] ?? $room->jenis }}</td>
                    <td class="td">{{ $room->kapasitas }} kursi</td>
                    <td class="td max-w-xs text-stone-600 dark:text-stone-300">{{ $room->fasilitas ?: '-' }}</td>
                    <td class="td">
                        <span class="inline-block rounded-full px-2.5 py-1 text-xs font-bold {{ $room->aktif ? 'bg-green-100 text-green-800 dark:bg-green-700/30 dark:text-green-200' : 'bg-stone-200 text-stone-700 dark:bg-stone-700 dark:text-stone-200' }}">{{ $room->aktif ? 'Aktif' : 'Nonaktif' }}</span>
                    </td>
                    <td class="td">
                        <div class="flex gap-1.5">
                            <button type="button" class="btn btn-ghost btn-sm" data-open="dlgRoom" data-title="Ubah ruangan" data-method="PUT"
                                    data-action="{{ route('admin.ruangan.update', $room) }}"
                                    data-item="{{ json_encode($room->only(['kode', 'nama', 'jenis', 'kapasitas', 'fasilitas', 'aktif'])) }}">Ubah</button>
                            <form method="POST" action="{{ route('admin.ruangan.destroy', $room) }}" onsubmit="return confirm('Hapus ruangan ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="p-8 text-center text-sm text-stone-500">Belum ada ruangan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<dialog id="dlgRoom" class="m-auto w-full max-w-lg rounded-2xl bg-white p-0 text-stone-800 shadow-2xl dark:bg-stone-900 dark:text-stone-100">
    <form method="POST" class="space-y-3 p-5">
        @csrf
        <input type="hidden" name="_method" value="POST">
        <div class="flex items-center justify-between">
            <h2 data-title class="text-lg font-extrabold">Tambah ruangan</h2>
            <button type="button" data-close class="rounded-lg p-2 hover:bg-stone-100 dark:hover:bg-stone-800" aria-label="Tutup"><svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6 6 18"/></svg></button>
        </div>

        <div class="grid gap-3 sm:grid-cols-2">
            <label class="block text-sm font-semibold">Kode
                <input name="kode" required maxlength="12" class="field" placeholder="Contoh: A103">
            </label>
            <label class="block text-sm font-semibold">Nama ruangan
                <input name="nama" required maxlength="60" class="field" placeholder="Contoh: Ruang A.103">
            </label>
            <label class="block text-sm font-semibold">Jenis
                <select name="jenis" required class="field">
                    @foreach (\App\Models\Room::JENIS as $k => $v)<option value="{{ $k }}">{{ $v }}</option>@endforeach
                </select>
            </label>
            <label class="block text-sm font-semibold">Kapasitas (kursi)
                <input type="number" name="kapasitas" required min="1" max="1000" value="30" class="field">
            </label>
        </div>
        <label class="block text-sm font-semibold">Fasilitas <span class="font-normal text-stone-500">(pisahkan dengan koma)</span>
            <input name="fasilitas" maxlength="200" class="field" placeholder="AC, Proyektor, Papan tulis">
        </label>
        <label class="flex items-center gap-2 text-sm font-semibold">
            <input type="checkbox" name="aktif" value="1" checked class="h-4 w-4 accent-[#f25f0f]">
            Ruangan aktif dan bisa dipinjam
        </label>

        <div class="flex justify-end gap-2 pt-1">
            <button type="button" data-close class="btn btn-ghost">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</dialog>
@endsection