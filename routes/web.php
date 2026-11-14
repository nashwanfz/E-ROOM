<?php

use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

// Hanya untuk yang BELUM login
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:6,1')   // maks. 6 percobaan per menit
        ->name('login.attempt');
});

// Untuk semua yang SUDAH login
Route::middleware('auth')->group(function () {
    // Mahasiswa -> halaman E-ROOM, admin -> dashboard admin
    Route::get('/', function () {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : view('welcome');
    })->name('home');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Khusus ADMIN
Route::middleware(['auth', EnsureUserIsAdmin::class])
    ->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('ruangan', RoomController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->parameters(['ruangan' => 'room']);

        Route::resource('peminjaman', BookingController::class)
            ->only(['index', 'store', 'destroy'])
            ->parameters(['peminjaman' => 'booking']);
        Route::patch('peminjaman/{booking}/status', [BookingController::class, 'status'])->name('peminjaman.status');

        Route::resource('mahasiswa', StudentController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->parameters(['mahasiswa' => 'user']);
    });