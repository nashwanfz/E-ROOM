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
        <form method="POST" action="{{ $url }}" onsubmit="const n = prompt('Alasan penolakan (boleh dikosongkan):'); if (n === null) return false; this.catatan_admin.value = n; return true;">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="ditolak">
            <input type="hidden" name="catatan_admin" value="">
            <button class="btn btn-ghost btn-sm">Tolak</button>
        </form>
    @endif
    @if ($b->status === 'disetujui')
        <form method="POST" action="{{ $url }}" onsubmit="return confirm('Batalkan peminjaman ini?')">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="dibatalkan">
            <button class="btn btn-ghost btn-sm">Batalkan</button>
        </form>
    @endif
</div>