<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Mahasiswa\BookingController as MahasiswaBookingController;
use App\Http\Controllers\Mahasiswa\KalenderController;
use App\Http\Controllers\Mahasiswa\KetersediaanController;
use App\Http\Controllers\Mahasiswa\LaporanController as MahasiswaLaporanController;
use App\Http\Controllers\Mahasiswa\PeminjamankuController;
use Illuminate\Support\Facades\Route;

// Hanya untuk yang BELUM login
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:6,1')   // maks. 6 percobaan per menit
        ->name('login.attempt');
});

// Logout (POST)
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Halaman utama / redirect sesuai role (RBAC)
Route::middleware('auth')->get('/', function () {
    $role = auth()->user()->role;
    if (in_array($role, ['admin', 'super_admin'], true)) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('mahasiswa.ketersediaan');
})->name('home');

// Khusus MAHASISWA (Peminjam)
Route::middleware(['auth', 'mahasiswa'])->group(function () {
    Route::get('/ketersediaan', [KetersediaanController::class, 'index'])->name('mahasiswa.ketersediaan');
    Route::get('/kalender', [KalenderController::class, 'index'])->name('mahasiswa.kalender');
    Route::get('/peminjamanku', [PeminjamankuController::class, 'index'])->name('mahasiswa.peminjamanku');
    Route::patch('/peminjamanku/{booking}/batal', [PeminjamankuController::class, 'cancel'])->name('mahasiswa.peminjamanku.cancel');
    Route::get('/peminjamanku/ekspor-ics', [PeminjamankuController::class, 'exportIcs'])->name('mahasiswa.peminjamanku.ics');
    Route::post('/peminjaman', [MahasiswaBookingController::class, 'store'])->name('mahasiswa.peminjaman.store');
    Route::get('/laporan', [MahasiswaLaporanController::class, 'index'])->name('mahasiswa.laporan');
});

// Khusus PENGELOLA (ADMIN & SUPER_ADMIN)
Route::middleware(['auth', 'admin'])
    ->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('ruangan', RoomController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->parameters(['ruangan' => 'room']);

        Route::resource('peminjaman', AdminBookingController::class)
            ->only(['index', 'store', 'destroy'])
            ->parameters(['peminjaman' => 'booking']);
        Route::patch('peminjaman/{booking}/status', [AdminBookingController::class, 'status'])->name('peminjaman.status');

        Route::resource('mahasiswa', StudentController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->parameters(['mahasiswa' => 'user']);

        Route::get('laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');

        // Khusus SUPER_ADMIN
        Route::middleware('super_admin')->group(function () {
            Route::resource('pengelola', AdminUserController::class)
                ->only(['index', 'store', 'update', 'destroy'])
                ->parameters(['pengelola' => 'user']);
        });
    });
