<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Tepat 3 role: super_admin, admin (pengelola), mahasiswa (peminjam)
     */
    public function run(): void
    {
        // 1. Super Admin (Login via email + password)
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@ft.unmul.ac.id'],
            [
                'nim'           => '1000000000',
                'name'          => 'Super Admin E-ROOM',
                'prodi'         => 'Fakultas Teknik',
                'program_studi' => 'Fakultas Teknik',
                'role'          => 'super_admin',
                'password'      => 'password',
            ]
        );

        // 2. Admin Pengelola (Login via email + password)
        $admin = User::updateOrCreate(
            ['email' => 'admin@ft.unmul.ac.id'],
            [
                'nim'           => '1000000001',
                'name'          => 'Admin Pengelola',
                'prodi'         => 'Fakultas Teknik',
                'program_studi' => 'Fakultas Teknik',
                'role'          => 'admin',
                'password'      => 'password',
            ]
        );

        // 3. Mahasiswa (Login via NIM + password)
        // 5 akun mahasiswa resmi: role mahasiswa, prodi Informatika, password: password
        $mahasiswaList = [
            [
                'nim'           => '2309106125',
                'name'          => 'Nashwan Faiz Nandana Astaman',
                'email'         => 'nashwan@student.unmul.ac.id',
                'prodi'         => 'Informatika',
                'program_studi' => 'Informatika',
                'role'          => 'mahasiswa',
                'password'      => 'password',
            ],
            [
                'nim'           => '2309106135',
                'name'          => 'Nanda Arianto',
                'email'         => 'nanda@student.unmul.ac.id',
                'prodi'         => 'Informatika',
                'program_studi' => 'Informatika',
                'role'          => 'mahasiswa',
                'password'      => 'password',
            ],
            [
                'nim'           => '2409106008',
                'name'          => 'Intan Alfara Audia',
                'email'         => 'intan@student.unmul.ac.id',
                'prodi'         => 'Informatika',
                'program_studi' => 'Informatika',
                'role'          => 'mahasiswa',
                'password'      => 'password',
            ],
            [
                'nim'           => '2409106021',
                'name'          => 'Rusdiana',
                'email'         => 'rusdiana@student.unmul.ac.id',
                'prodi'         => 'Informatika',
                'program_studi' => 'Informatika',
                'role'          => 'mahasiswa',
                'password'      => 'password',
            ],
            [
                'nim'           => '2409106002',
                'name'          => 'Renaya Putri Alika',
                'email'         => 'renaya@student.unmul.ac.id',
                'prodi'         => 'Informatika',
                'program_studi' => 'Informatika',
                'role'          => 'mahasiswa',
                'password'      => 'password',
            ],
        ];

        $mahasiswaModels = [];
        foreach ($mahasiswaList as $mhs) {
            $mahasiswaModels[$mhs['nim']] = User::updateOrCreate(['nim' => $mhs['nim']], $mhs);
        }

        // Ruangan (9 Ruangan di Fakultas Teknik Universitas Mulawarman)
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

        $roomModels = [];
        foreach ($rooms as [$kode, $nama, $jenis, $kap, $fas]) {
            $roomModels[$kode] = Room::updateOrCreate(
                ['kode' => $kode],
                [
                    'nama'      => $nama,
                    'jenis'     => $jenis,
                    'kapasitas' => $kap,
                    'fasilitas' => $fas,
                    'aktif'     => true,
                ]
            );
        }

        // Peminjaman (Booking Samples)
        $bookings = [
            [
                'nim'            => '2309106135',
                'kode'           => 'A101',
                'hari'           => 0,
                'jam_mulai'      => '07:30',
                'jam_selesai'    => '09:30',
                'jumlah_peserta' => 30,
                'alasan'         => 'Kuliah pengganti Pemrograman Web',
                'status'         => 'disetujui',
                'catatan_admin'  => null,
            ],
            [
                'nim'            => '2309106125',
                'kode'           => 'LAB1',
                'hari'           => 0,
                'jam_mulai'      => '09:00',
                'jam_selesai'    => '12:00',
                'jumlah_peserta' => 25,
                'alasan'         => 'Praktikum tambahan Basis Data',
                'status'         => 'disetujui',
                'catatan_admin'  => null,
            ],
            [
                'nim'            => '2409106008',
                'kode'           => 'A101',
                'hari'           => 0,
                'jam_mulai'      => '13:00',
                'jam_selesai'    => '15:00',
                'jumlah_peserta' => 15,
                'alasan'         => 'Rapat persiapan lomba karya tulis ilmiah',
                'status'         => 'menunggu',
                'catatan_admin'  => null,
            ],
            [
                'nim'            => '2409106008',
                'kode'           => 'A102',
                'hari'           => 1,
                'jam_mulai'      => '09:00',
                'jam_selesai'    => '11:00',
                'jumlah_peserta' => 20,
                'alasan'         => 'Kerja kelompok tugas Struktur Data',
                'status'         => 'menunggu',
                'catatan_admin'  => null,
            ],
            [
                'nim'            => '2409106021',
                'kode'           => 'SEM1',
                'hari'           => 2,
                'jam_mulai'      => '10:00',
                'jam_selesai'    => '12:00',
                'jumlah_peserta' => 12,
                'alasan'         => 'Latihan presentasi sidang proposal',
                'status'         => 'menunggu',
                'catatan_admin'  => null,
            ],
            [
                'nim'            => '2409106002',
                'kode'           => 'B201',
                'hari'           => 3,
                'jam_mulai'      => '08:00',
                'jam_selesai'    => '11:00',
                'jumlah_peserta' => 45,
                'alasan'         => 'Sosialisasi program kerja ormawa',
                'status'         => 'disetujui',
                'catatan_admin'  => 'Harap menjaga kebersihan ruangan',
            ],
            [
                'nim'            => '2309106135',
                'kode'           => 'AULA',
                'hari'           => 5,
                'jam_mulai'      => '08:00',
                'jam_selesai'    => '15:00',
                'jumlah_peserta' => 150,
                'alasan'         => 'Seminar Teknologi Fakultas Teknik',
                'status'         => 'disetujui',
                'catatan_admin'  => 'Gunakan proyektor dan sound system sesuai SOP',
            ],
        ];

        foreach ($bookings as $b) {
            $user = $mahasiswaModels[$b['nim']];
            $room = $roomModels[$b['kode']];
            Booking::firstOrCreate(
                [
                    'user_id'   => $user->id,
                    'room_id'   => $room->id,
                    'tanggal'   => now()->addDays($b['hari'])->toDateString(),
                    'jam_mulai' => $b['jam_mulai'],
                ],
                [
                    'jam_selesai'    => $b['jam_selesai'],
                    'jumlah_peserta' => $b['jumlah_peserta'],
                    'alasan'         => $b['alasan'],
                    'status'         => $b['status'],
                    'catatan_admin'  => $b['catatan_admin'],
                ]
            );
        }
    }
}
