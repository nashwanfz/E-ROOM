<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Akun admin. GANTI kata sandinya sebelum dipakai sungguhan.
        User::updateOrCreate(['nim' => '1000000001'], [
            'name' => 'Admin E-ROOM', 'prodi' => 'Fakultas Teknik', 'role' => 'admin', 'password' => 'admin12345',
        ]);

        // Ruangan (sama dengan daftar di halaman mahasiswa)
        $rooms = [
            ['A101', 'Ruang A.101', 'kuliah', 40, 'AC, Proyektor, Papan tulis'],
            ['A102', 'Ruang A.102', 'kuliah', 40, 'AC, Proyektor, Papan tulis'],
            ['B201', 'Ruang B.201', 'kuliah', 60, 'AC, Proyektor, Sound system'],
            ['B202', 'Ruang B.202', 'kuliah', 50, 'AC, Proyektor'],
            ['LAB1', 'Lab Komputer 1', 'lab', 30, 'AC, 30 PC, Proyektor'],
            ['LAB2', 'Lab Jaringan', 'lab', 25, 'AC, Perangkat jaringan, Proyektor'],
            ['SEM1', 'Ruang Seminar TA 1', 'seminar', 25, 'AC, TV 55 inci, Meja rapat'],
            ['SEM2', 'Ruang Seminar TA 2', 'seminar', 25, 'AC, TV 55 inci, Meja rapat'],
            ['AULA', 'Aula Fakultas', 'aula', 200, 'AC, Panggung, Sound system, Proyektor'],
        ];
        foreach ($rooms as [$kode, $nama, $jenis, $kap, $fas]) {
            Room::updateOrCreate(['kode' => $kode], [
                'nama' => $nama, 'jenis' => $jenis, 'kapasitas' => $kap, 'fasilitas' => $fas, 'aktif' => true,
            ]);
        }

        // Mahasiswa contoh + beberapa peminjaman contoh (hapus bila tidak perlu)
        $mhs = [];
        foreach ([['2509106001', 'Mahasiswa Contoh', 'Informatika'],
                  ['2409106031', 'Rizky Maulana', 'Informatika'],
                  ['2411106014', 'Sinta Dewi Lestari', 'Teknik Sipil']] as [$nim, $nama, $prodi]) {
            $mhs[$nim] = User::updateOrCreate(['nim' => $nim], [
                'name' => $nama, 'prodi' => $prodi, 'role' => 'mahasiswa', 'password' => 'password123',
            ]);
        }

        $room = fn (string $kode) => Room::where('kode', $kode)->first();
        $demo = [
            ['2409106031', 'A101', 0, '07:30', '09:30', 30, 'Kuliah pengganti Pemrograman Web', 'disetujui'],
            ['2509106001', 'LAB1', 0, '09:00', '12:00', 25, 'Praktikum tambahan Basis Data', 'disetujui'],
            ['2411106014', 'A101', 0, '13:00', '15:00', 15, 'Rapat persiapan lomba karya tulis ilmiah', 'menunggu'],
            ['2411106014', 'A102', 1, '09:00', '11:00', 20, 'Kerja kelompok tugas Struktur Beton', 'menunggu'],
            ['2409106031', 'SEM1', 2, '10:00', '12:00', 12, 'Latihan presentasi sidang skripsi', 'menunggu'],
        ];
        foreach ($demo as [$nim, $kode, $plus, $mulai, $selesai, $pax, $alasan, $status]) {
            Booking::firstOrCreate([
                'user_id' => $mhs[$nim]->id, 'room_id' => $room($kode)->id,
                'tanggal' => now()->addDays($plus)->toDateString(), 'jam_mulai' => $mulai,
            ], ['jam_selesai' => $selesai, 'jumlah_peserta' => $pax, 'alasan' => $alasan, 'status' => $status]);
        }
    }
}