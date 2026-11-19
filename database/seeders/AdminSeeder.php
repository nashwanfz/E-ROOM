<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Panggil seeder utama DatabaseSeeder
        $this->call(DatabaseSeeder::class);
    }
}
