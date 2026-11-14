@extends('admin.layout')
@section('title', 'Peminjaman')
@section('heading', 'Kelola peminjaman')

@section('content')
<div class="flex flex-wrap items-end gap-3">
    <div class="mr-auto">
        <h2 class="text-lg font-extrabold">Semua peminjaman</h2>
        <p class="text-sm text-stone-500">{{ $bookings->total() }} peminjaman ditemukan.</p>
    </div>
    <button type="button" class="btn btn-primary" data-open="dlgBooking" data-action="{{ route('admin.peminjaman.store') }}" data-title="Tambah peminjaman">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
        Tambah peminjaman
    </button>
</div>

<form method="GET" class="card grid gap-3 p-4 sm:grid-cols-2 lg:grid-cols-5">
    <label class="block text-xs font-semibold lg:col-span-2">Cari mahasiswa atau alasan
        <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Nama, NIM, atau alasan" class="field">
    </label>
    <label class="block text-xs font-semibold">Tanggal
        <input type="date" name="tanggal" value="{{ $filters['tanggal'] ?? '' }}" class="field">
    </label>
    <label class="block text-xs font-semibold">Ruangan
        <select name="room_id" class="field">
            <option value="">Semua ruangan</option>
            @foreach ($rooms as $r)<option value="{{ $r->id }}" @selected(($filters['room_id'] ?? '') == $r->id)>{{ $r->nama }}</option>@endforeach
        </select>
    </label>
    <label class="block text-xs font-semibold">Status
        <select name="status" class="field">
            <option value="">Semua status</option>
            @foreach (\App\Models\Booking::STATUS as $s)<option value="{{ $s }}" @selected(($filters['status'] ?? '') === $s)>{{ ucfirst($s) }}</option>@endforeach
        </select>
    </label>
    <div class="flex gap-2 sm:col-span-2 lg:col-span-5">
        <button class="btn btn-green">Terapkan filter</button>
        <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-ghost">Reset</a>
    </div>
</form>

<div class="card overflow-x-auto">
    <table class="w-full min-w-[860px] text-left">
        <thead class="border-b border-stone-200 bg-stone-50 dark:border-stone-800 dark:bg-stone-800/50">
            <tr><th class="th">Waktu</th><th class="th">Ruangan</th><th class="th">Peminjam</th><th class="th">Alasan</th><th class="th">Status</th><th class="th">Aksi</th></tr>
        </thead>
        <tbody class="divide-y divide-stone-200 dark:divide-stone-800">
            @forelse ($bookings as $b)
                <tr>
                    <td class="td whitespace-nowrap"><p class="font-bold">{{ $b->tanggal->format('d/m/Y') }}</p><p class="text-xs text-stone-500">{{ $b->jam_mulai }} - {{ $b->jam_selesai }}</p></td>
                    <td class="td"><p class="font-semibold">{{ $b->room->nama }}</p><p class="text-xs text-stone-500">{{ $b->jumlah_peserta }} peserta</p></td>
                    <td class="td"><p class="font-semibold">{{ $b->user->name }}</p><p class="text-xs text-stone-500">{{ $b->user->nim }} &bull; {{ $b->user->prodi }}</p></td>
                    <td class="td max-w-xs"><p>{{ $b->alasan }}</p>@if ($b->catatan_admin)<p class="mt-1 text-xs text-red-600">Catatan admin: {{ $b->catatan_admin }}</p>@endif</td>
                    <td class="td">@include('admin._status', ['status' => $b->status])</td>
                    <td class="td">
                        <div class="flex flex-col items-start gap-1.5">
                            @include('admin._booking_actions', ['b' => $b])
                            <form method="POST" action="{{ route('admin.peminjaman.destroy', $b) }}" onsubmit="return confirm('Hapus peminjaman ini secara permanen?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="p-8 text-center text-sm text-stone-500">Tidak ada peminjaman yang cocok dengan filter.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div>{{ $bookings->links() }}</div>

{{-- Modal tambah peminjaman --}}
<dialog id="dlgBooking" class="m-auto w-full max-w-lg rounded-2xl bg-white p-0 text-stone-800 shadow-2xl dark:bg-stone-900 dark:text-stone-100">
    <form method="POST" class="space-y-3 p-5">
        @csrf
        <input type="hidden" name="_method" value="POST">
        <div class="flex items-center justify-between">
            <h2 data-title class="text-lg font-extrabold">Tambah peminjaman</h2>
            <button type="button" data-close class="rounded-lg p-2 hover:bg-stone-100 dark:hover:bg-stone-800" aria-label="Tutup"><svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6 6 18"/></svg></button>
        </div>
        <p class="text-xs text-stone-500">Peminjaman yang ditambahkan admin langsung berstatus disetujui.</p>

        <label class="block text-sm font-semibold">Mahasiswa
            <select name="user_id" required class="field">
                <option value="">Pilih mahasiswa</option>
                @foreach ($students as $s)<option value="{{ $s->id }}">{{ $s->name }} ({{ $s->nim }})</option>@endforeach
            </select>
        </label>
        <label class="block text-sm font-semibold">Ruangan
            <select name="room_id" required class="field">
                @foreach ($rooms->where('aktif', true) as $r)<option value="{{ $r->id }}">{{ $r->nama }} ({{ $r->kapasitas }} kursi)</option>@endforeach
            </select>
        </label>
        <div class="grid gap-3 sm:grid-cols-3">
            <label class="block text-sm font-semibold sm:col-span-3">Tanggal
                <input type="date" name="tanggal" required min="{{ date('Y-m-d') }}" class="field">
            </label>
            <label class="block text-sm font-semibold">Jam mulai
                <input type="time" name="jam_mulai" required min="07:00" max="20:00" class="field">
            </label>
            <label class="block text-sm font-semibold">Jam selesai
                <input type="time" name="jam_selesai" required min="07:00" max="20:00" class="field">
            </label>
            <label class="block text-sm font-semibold">Peserta
                <input type="number" name="jumlah_peserta" required min="1" value="10" class="field">
            </label>
        </div>
        <label class="block text-sm font-semibold">Alasan peminjaman
            <textarea name="alasan" required minlength="10" maxlength="200" rows="3" class="field" placeholder="Contoh: Rapat persiapan lomba"></textarea>
        </label>

        <div class="flex justify-end gap-2 pt-1">
            <button type="button" data-close class="btn btn-ghost">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan peminjaman</button>
        </div>
    </form>
</dialog>
@endsection