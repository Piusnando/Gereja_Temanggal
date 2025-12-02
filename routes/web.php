<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PageController;
use App\Http\Controllers\FeedbackController; // Public
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\LiturgyController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController; // Admin

/*
|--------------------------------------------------------------------------
| Web Routes (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/sejarah', [PageController::class, 'sejarah']);
Route::get('/pengumuman', [PageController::class, 'pengumuman']);
Route::get('/pengumuman/{id}', [PageController::class, 'detailPengumuman'])->name('pengumuman.detail');
Route::get('/teritorial', [PageController::class, 'teritorial'])->name('teritorial.index');
Route::get('/teritorial/{slug}', [PageController::class, 'showTeritorial'])->name('teritorial.show');
Route::get('/organisasi', [PageController::class, 'organisasi']);
Route::get('/jadwal-petugas', [PageController::class, 'jadwalPetugas'])->name('jadwal.petugas');
Route::get('/petugas/{role}', [PageController::class, 'showPetugasRole'])->name('petugas.role');

// Kirim Pesan (Public)
Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

/*
|--------------------------------------------------------------------------
| Auth Routes (Login/Logout)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (PROTECTED)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->group(function () {

    // 1. DASHBOARD & PROFILE (Bisa diakses SEMUA role yang login)
    Route::get('/dashboard', function() { return view('admin.dashboard'); })->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('admin.profile.password');


    // 2. KHUSUS ADMIN (Logo, Banner, Settings Sistem)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
        Route::post('/settings/logo', [SettingController::class, 'updateLogo'])->name('admin.settings.logo');
        Route::post('/banners', [SettingController::class, 'storeBanner'])->name('admin.banners.store');
        Route::delete('/banners/{id}', [SettingController::class, 'destroyBanner'])->name('admin.banners.destroy');
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class, ['as' => 'admin']);
    });


    // 3. ADMIN & PENGURUS GEREJA (Pengumuman, Kritik Saran)
    Route::middleware(['role:admin,pengurus_gereja'])->group(function () {
        Route::resource('announcements', AnnouncementController::class, ['as' => 'admin']);
        Route::get('/feedback', [AdminFeedbackController::class, 'index'])->name('admin.feedback.index');
        Route::delete('/feedback/{id}', [AdminFeedbackController::class, 'destroy'])->name('admin.feedback.destroy');
    });


    // 4. ADMIN & DIREKTUR MUSIK (Kelola Petugas Musik & Jadwal)
    // Direktur musik boleh mengatur jadwal karena musik bagian dari liturgi
    Route::middleware(['role:admin,direktur_musik'])->group(function () {
        // Bisa akses database petugas Mazmur, Organis, Padus
        // Note: Logic filter tipe petugas bisa ditambahkan di Controller jika ingin strict
    });


    // 5. ZONA LITURGI (Kompleks: Admin, Misdinar, Lektor, DirMusik semua bisa akses tapi beda hak)
    // Kita buka akses route-nya untuk semua role liturgi, tapi nanti tombolnya kita sembunyikan di View
    Route::middleware(['role:admin,direktur_musik,misdinar,lektor,pengurus_gereja'])->group(function () {
        
        // A. DATABASE PETUGAS
        Route::get('/liturgy/personnels', [LiturgyController::class, 'personnelIndex'])->name('admin.liturgy.personnels');
        Route::get('/liturgy/personnels/create', [LiturgyController::class, 'personnelCreate'])->name('admin.liturgy.personnels.create');
        Route::post('/liturgy/personnels', [LiturgyController::class, 'personnelStore'])->name('admin.liturgy.personnels.store');

        // B. LIHAT DAFTAR JADWAL (Semua Role boleh lihat)
        Route::get('/liturgy/schedules', [LiturgyController::class, 'scheduleIndex'])->name('admin.liturgy.schedules');

        // C. MEMBUAT JADWAL BARU (Hanya Admin, Pengurus, Dir. Musik)
        // Misdinar & Lektor TIDAK BOLEH buat jadwal baru, cuma boleh ngisi.
        Route::middleware(['role:admin,pengurus_gereja,direktur_musik'])->group(function() {
            Route::get('/liturgy/schedules/create', [LiturgyController::class, 'scheduleCreate'])->name('admin.liturgy.schedules.create');
            Route::post('/liturgy/schedules', [LiturgyController::class, 'scheduleStore'])->name('admin.liturgy.schedules.store');
        });
        
        // D. MENGATUR/MENGISI PETUGAS (Admin, Pengurus, Dir. Musik, Misdinar, Lektor)
        // PERBAIKAN: Tambahkan 'misdinar' dan 'lektor' di sini agar tidak Error 403
        Route::middleware(['role:admin,pengurus_gereja,direktur_musik,misdinar,lektor'])->group(function() {
            Route::get('/liturgy/schedules/{id}/assign', [LiturgyController::class, 'scheduleEdit'])->name('admin.liturgy.assign');
            Route::post('/liturgy/schedules/{id}/assign', [LiturgyController::class, 'assignmentStore'])->name('admin.liturgy.assign.store');
            Route::delete('/liturgy/assignments/{id}', [LiturgyController::class, 'assignmentDestroy'])->name('admin.liturgy.assign.destroy');
        });

        // E. MENGHAPUS DATA PETUGAS (Hanya Admin)
        Route::delete('/liturgy/personnels/{id}', [LiturgyController::class, 'personnelDestroy'])->name('admin.liturgy.personnels.destroy');
    });

});