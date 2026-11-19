<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(DatabaseSeeder::class);
    }
}
