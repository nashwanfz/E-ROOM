@php $url = route('admin.peminjaman.status', $b); @endphp
<div class="flex flex-wrap gap-1.5">
    @if (in_array($b->status, ['menunggu', 'ditolak', 'dibatalkan']))
        <form method="POST" action="{{ $url }}">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="disetujui">
            <button class="btn btn-green btn-sm">Setujui</button>
        </form>
    @endif
    @if ($b->status === 'menunggu')
        <button type="button" onclick="openRejectModal('{{ $url }}')" class="btn btn-ghost btn-sm text-red-600">Tolak</button>
    @endif
    @if ($b->status === 'disetujui')
        <form method="POST" action="{{ $url }}">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="dibatalkan">
            <button type="button" onclick="showAdminConfirmModal(this.form, 'Batalkan Peminjaman', 'Apakah Anda yakin ingin membatalkan peminjaman {{ $b->room->nama ?? '' }} oleh {{ $b->user->name ?? '' }}?')" class="btn btn-ghost btn-sm">Batalkan</button>
        </form>
    @endif
</div>