<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    public const JENIS = [
        'kuliah'  => 'Ruang kuliah',
        'lab'     => 'Laboratorium',
        'seminar' => 'Ruang seminar',
        'aula'    => 'Aula',
    ];

    protected $fillable = ['kode', 'nama', 'jenis', 'kapasitas', 'fasilitas', 'aktif'];

    protected function casts(): array
    {
        return ['aktif' => 'boolean'];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}