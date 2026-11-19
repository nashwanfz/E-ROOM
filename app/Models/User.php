<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'nim', 'prodi', 'program_studi', 'role', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function setProdiAttribute($value): void
    {
        $this->attributes['prodi'] = $value;
        $this->attributes['program_studi'] = $value;
    }

    public function setProgramStudiAttribute($value): void
    {
        $this->attributes['program_studi'] = $value;
        if (! isset($this->attributes['prodi'])) {
            $this->attributes['prodi'] = $value;
        }
    }

    public function getProgramStudiAttribute(): ?string
    {
        return $this->attributes['program_studi'] ?? $this->attributes['prodi'] ?? null;
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
