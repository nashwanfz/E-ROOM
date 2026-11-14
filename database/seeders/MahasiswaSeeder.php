<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        // Akun contoh untuk uji coba. Ganti/hapus sebelum dipakai sungguhan.
        User::updateOrCreate(
            ['nim' => '2509106001'],
            [
                'name'     => 'Mahasiswa Contoh',
                'prodi'    => 'Informatika',
                'password' => Hash::make('password123'),
            ]
        );
    }
}
