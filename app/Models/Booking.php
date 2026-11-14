<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    public const STATUS = ['menunggu', 'disetujui', 'ditolak', 'dibatalkan'];

    protected $fillable = [
        'user_id', 'room_id', 'tanggal', 'jam_mulai', 'jam_selesai',
        'jumlah_peserta', 'alasan', 'status', 'catatan_admin',
    ];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /** Apakah jadwal ini bentrok dengan peminjaman aktif (menunggu/disetujui) di ruangan yang sama? */
    public static function hasConflict(int $roomId, string $tanggal, string $mulai, string $selesai, ?int $ignoreId = null): bool
    {
        return static::where('room_id', $roomId)
            ->whereDate('tanggal', $tanggal)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('jam_mulai', '<', $selesai)
            ->where('jam_selesai', '>', $mulai)
            ->exists();
    }
}