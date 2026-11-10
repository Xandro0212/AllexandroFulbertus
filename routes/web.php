<?php

use App\Http\Controllers\AcademicController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

// Endpoint autentikasi untuk channel privat (Laravel Echo)
Broadcast::routes(['middleware' => ['web']]);

// ===== Halaman Publik =====
Route::get('/', fn () => view('landing'))->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

// ===== Halaman Terautentikasi =====
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kalender / jadwal kelas (semua peran)
    Route::get('/jadwal', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/ruangan', [ScheduleController::class, 'classrooms'])->name('schedule.classrooms');

    // Notifikasi (semua peran)
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notification.index');
    Route::post('/notifikasi/{notification}/baca', [NotificationController::class, 'markRead'])->name('notification.read');
    Route::post('/notifikasi/baca-semua', [NotificationController::class, 'markAllRead'])->name('notification.readAll');

    // Pengajuan peminjaman (mahasiswa)
    Route::middleware('role:mahasiswa')->group(function () {
        Route::get('/pengajuan', [BookingController::class, 'index'])->name('booking.index');
        Route::get('/pengajuan/buat', [BookingController::class, 'create'])->name('booking.create');
        Route::post('/pengajuan', [BookingController::class, 'store'])->name('booking.store');
        Route::get('/pengajuan/{booking}', [BookingController::class, 'show'])->name('booking.show');
        Route::post('/pengajuan/{booking}/batal', [BookingController::class, 'cancel'])->name('booking.cancel');
    });

    // Persetujuan akademik
    Route::middleware('role:akademik,admin')->group(function () {
        Route::get('/akademik', [AcademicController::class, 'index'])->name('academic.index');
        Route::get('/akademik/{booking}', [AcademicController::class, 'show'])->name('academic.show');
        Route::post('/akademik/{booking}/setujui', [AcademicController::class, 'approve'])->name('academic.approve');
        Route::post('/akademik/{booking}/tolak', [AcademicController::class, 'reject'])->name('academic.reject');
    });

    // Manajemen admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/pengguna', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/pengguna/buat', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/admin/pengguna', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/admin/pengguna/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/admin/pengguna/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/admin/pengguna/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');

        Route::get('/admin/ruangan', [AdminController::class, 'classrooms'])->name('admin.classrooms');
        Route::get('/admin/ruangan/buat', [AdminController::class, 'createClassroom'])->name('admin.classrooms.create');
        Route::post('/admin/ruangan', [AdminController::class, 'storeClassroom'])->name('admin.classrooms.store');
        Route::get('/admin/ruangan/{classroom}/edit', [AdminController::class, 'editClassroom'])->name('admin.classrooms.edit');
        Route::put('/admin/ruangan/{classroom}', [AdminController::class, 'updateClassroom'])->name('admin.classrooms.update');
        Route::delete('/admin/ruangan/{classroom}', [AdminController::class, 'destroyClassroom'])->name('admin.classrooms.destroy');

        Route::get('/admin/pengajuan', [AdminController::class, 'bookings'])->name('admin.bookings');
    });
});
