<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductionUpgradeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /** Task 2: Seeder tepat 3 role, 5 akun mahasiswa spesifik Informatika, admin & super_admin FT */
    public function test_seeder_creates_three_roles_rooms_and_bookings(): void
    {
        // 3 role
        $this->assertDatabaseHas('users', ['role' => 'super_admin', 'email' => 'superadmin@ft.unmul.ac.id']);
        $this->assertDatabaseHas('users', ['role' => 'admin', 'email' => 'admin@ft.unmul.ac.id']);

        $mahasiswaNims = [
            '2309106125',
            '2309106135',
            '2409106008',
            '2409106021',
            '2409106002',
        ];

        foreach ($mahasiswaNims as $nim) {
            $this->assertDatabaseHas('users', [
                'role'          => 'mahasiswa',
                'nim'           => $nim,
                'program_studi' => 'Informatika',
                'prodi'         => 'Informatika',
            ]);
        }

        $this->assertEquals(5, User::where('role', 'mahasiswa')->count());

        $rolesInDb = User::distinct()->pluck('role')->sort()->values()->all();
        $this->assertEquals(['admin', 'mahasiswa', 'super_admin'], $rolesInDb);

        // Ruangan
        $this->assertGreaterThanOrEqual(9, Room::count());
        $this->assertDatabaseHas('rooms', ['kode' => 'A101']);
        $this->assertDatabaseHas('rooms', ['kode' => 'AULA']);

        // Booking
        $this->assertGreaterThanOrEqual(5, Booking::count());
    }

    /** Task 1: Login via satu form - Mahasiswa via NIM, Admin & Super Admin via Email */
    public function test_mahasiswa_can_login_with_nim_and_password(): void
    {
        $response = $this->post(route('login'), [
            'login'    => '2309106135',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('mahasiswa.ketersediaan'));
        $this->assertAuthenticated();
        $this->assertEquals('2309106135', auth()->user()->nim);
        $this->assertEquals('mahasiswa', auth()->user()->role);
    }

    public function test_admin_can_login_with_email_and_password(): void
    {
        $response = $this->post(route('login'), [
            'login'    => 'admin@ft.unmul.ac.id',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
        $this->assertEquals('admin@ft.unmul.ac.id', auth()->user()->email);
        $this->assertEquals('admin', auth()->user()->role);
    }

    public function test_super_admin_can_login_with_email_and_password(): void
    {
        $response = $this->post(route('login'), [
            'login'    => 'superadmin@ft.unmul.ac.id',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
        $this->assertEquals('superadmin@ft.unmul.ac.id', auth()->user()->email);
        $this->assertEquals('super_admin', auth()->user()->role);
    }

    public function test_mahasiswa_cannot_login_with_email(): void
    {
        $response = $this->post(route('login'), [
            'login'    => 'nanda@student.unmul.ac.id',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_admin_cannot_login_with_nim(): void
    {
        $response = $this->post(route('login'), [
            'login'    => '1000000001',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_login_fails_with_invalid_password(): void
    {
        $response = $this->post(route('login'), [
            'login'    => '2309106135',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    /** Task 2 & Task 5: Sidebar per page sendiri & RBAC Mahasiswa */
    public function test_mahasiswa_can_access_each_own_menu_page(): void
    {
        $mhs = User::where('role', 'mahasiswa')->first();

        // Menu 1: Ketersediaan
        $resKetersediaan = $this->actingAs($mhs)->get(route('mahasiswa.ketersediaan'));
        $resKetersediaan->assertStatus(200);
        $resKetersediaan->assertSee('Ketersediaan Ruangan');

        // Menu 2: Kalender
        $resKalender = $this->actingAs($mhs)->get(route('mahasiswa.kalender'));
        $resKalender->assertStatus(200);
        $resKalender->assertSee('Kalender Peminjaman');

        // Menu 3: Peminjamanku
        $resMine = $this->actingAs($mhs)->get(route('mahasiswa.peminjamanku'));
        $resMine->assertStatus(200);
        $resMine->assertSee('Peminjamanku');

        // Menu 4 (Task 7): Laporan
        $resLaporan = $this->actingAs($mhs)->get(route('mahasiswa.laporan'));
        $resLaporan->assertStatus(200);
        $resLaporan->assertSee('Laporan Peminjaman');
    }

    /** Task 5: RBAC - Mahasiswa tidak boleh bisa mengakses fitur Admin */
    public function test_mahasiswa_cannot_access_admin_features(): void
    {
        $mhs = User::where('role', 'mahasiswa')->first();

        $this->actingAs($mhs)->get('/admin')->assertStatus(403);
        $this->actingAs($mhs)->get('/admin/ruangan')->assertStatus(403);
        $this->actingAs($mhs)->get('/admin/peminjaman')->assertStatus(403);
        $this->actingAs($mhs)->get('/admin/mahasiswa')->assertStatus(403);
        $this->actingAs($mhs)->get('/admin/laporan')->assertStatus(403);
    }

    /** Task 5: RBAC - Admin dapat akses fitur admin tetapi tidak fitur super_admin khusus */
    public function test_admin_can_access_admin_portal_and_laporan(): void
    {
        $admin = User::where('role', 'admin')->first();

        $this->actingAs($admin)->get('/admin')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/ruangan')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/peminjaman')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/mahasiswa')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/laporan')->assertStatus(200);

        // Admin cannot access super_admin kelola-admin
        $this->actingAs($admin)->get('/admin/pengelola')->assertStatus(403);

        // Admin cannot access mahasiswa restricted routes
        $this->actingAs($admin)->get('/ketersediaan')->assertStatus(403);
        $this->actingAs($admin)->get('/peminjamanku')->assertStatus(403);
    }

    /** Task 5: RBAC - Super Admin dapat akses pengelola admin */
    public function test_super_admin_can_access_super_admin_features(): void
    {
        $superAdmin = User::where('role', 'super_admin')->first();

        $this->actingAs($superAdmin)->get('/admin')->assertStatus(200);
        $this->actingAs($superAdmin)->get('/admin/laporan')->assertStatus(200);
        $this->actingAs($superAdmin)->get('/admin/pengelola')->assertStatus(200);
    }

    /** Task 3: Mahasiswa dapat mengajukan peminjaman (store booking) */
    public function test_mahasiswa_can_store_booking(): void
    {
        $mhs = User::where('role', 'mahasiswa')->first();
        $room = Room::where('aktif', true)->first();

        $response = $this->actingAs($mhs)->post(route('mahasiswa.peminjaman.store'), [
            'room_id'        => $room->id,
            'tanggal'        => now()->addDays(10)->toDateString(),
            'jam_mulai'      => '10:00',
            'jam_selesai'    => '12:00',
            'jumlah_peserta' => 15,
            'alasan'         => 'Rapat evaluasi kegiatan mahasiswa',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('bookings', [
            'user_id' => $mhs->id,
            'room_id' => $room->id,
            'status'  => 'menunggu',
            'alasan'  => 'Rapat evaluasi kegiatan mahasiswa',
        ]);
    }

    /** Task 7: Mahasiswa dapat melihat laporan peminjamannya sendiri */
    public function test_mahasiswa_laporan_only_shows_own_bookings(): void
    {
        $mhs1 = User::where('role', 'mahasiswa')->first();
        $mhs2 = User::where('role', 'mahasiswa')->where('id', '!=', $mhs1->id)->first();

        $room = Room::first();

        Booking::create([
            'user_id' => $mhs1->id,
            'room_id' => $room->id,
            'tanggal' => now()->toDateString(),
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
            'jumlah_peserta' => 10,
            'alasan' => 'Booking Unik Mahasiswa Satu',
            'status' => 'disetujui',
        ]);

        Booking::create([
            'user_id' => $mhs2->id,
            'room_id' => $room->id,
            'tanggal' => now()->toDateString(),
            'jam_mulai' => '08:00',
            'jam_selesai' => '09:00',
            'jumlah_peserta' => 10,
            'alasan' => 'Booking Unik Mahasiswa Dua',
            'status' => 'disetujui',
        ]);

        $res = $this->actingAs($mhs1)->get(route('mahasiswa.laporan'));
        $res->assertStatus(200);
        $res->assertSee('Booking Unik Mahasiswa Satu');
        $res->assertDontSee('Booking Unik Mahasiswa Dua');
    }

    /** Task 10: Logout route works and invalidates session */
    public function test_logout_works(): void
    {
        $mhs = User::where('role', 'mahasiswa')->first();

        $response = $this->actingAs($mhs)->post(route('logout'));
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    /** Task 3: Light mode default */
    public function test_theme_defaults_to_light_mode(): void
    {
        $res = $this->get(route('login'));
        $res->assertStatus(200);
        $res->assertSee("localStorage.getItem('eroom.theme') || 'light'", false);
        $res->assertDontSee('prefers-color-scheme');
    }

    /** Task 4: Lingkup FT UNMUL */
    public function test_branding_mentions_fakultas_teknik_universitas_mulawarman(): void
    {
        $resLogin = $this->get(route('login'));
        $resLogin->assertStatus(200);
        $resLogin->assertSee('Fakultas Teknik Universitas Mulawarman');

        $mhs = User::where('role', 'mahasiswa')->first();
        $resMhs = $this->actingAs($mhs)->get(route('mahasiswa.ketersediaan'));
        $resMhs->assertStatus(200);
        $resMhs->assertSee('Fakultas Teknik Universitas Mulawarman');

        $admin = User::where('role', 'admin')->first();
        $resAdmin = $this->actingAs($admin)->get(route('admin.dashboard'));
        $resAdmin->assertStatus(200);
        $resAdmin->assertSee('Fakultas Teknik Universitas Mulawarman');
    }
}
