<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Admin\LiturgyController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Auth\LoginController; // <-- Tambahkan ini
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Publik (Bisa diakses siapa saja)
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/sejarah', [PageController::class, 'sejarah']);
Route::get('/pengumuman', [PageController::class, 'pengumuman']);
Route::get('/pengumuman/{id}', [PageController::class, 'detailPengumuman'])->name('pengumuman.detail');
// Route Detail Wilayah (Dinamis berdasarkan slug)
Route::get('/teritorial', [PageController::class, 'teritorial'])->name('teritorial.index');
Route::get('/teritorial/{slug}', [PageController::class, 'showTeritorial'])->name('teritorial.show');
Route::get('/organisasi', [PageController::class, 'organisasi']);
Route::get('/jadwal-petugas', [PageController::class, 'jadwalPetugas'])->name('jadwal.petugas');
Route::get('/petugas/{role}', [PageController::class, 'showPetugasRole'])->name('petugas.role');



//Route Public (Untuk kirim pesan dari Footer)
Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');


/*
|--------------------------------------------------------------------------
| Auth Routes (Login & Logout)
|--------------------------------------------------------------------------
*/
// Hanya bisa diakses tamu (yang belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');


/*
|--------------------------------------------------------------------------
| Admin Routes (Hanya Pengurus)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    // Halaman Pengaturan (Banner & Logo)
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
    
    // Proses Upload
    Route::post('/settings/logo', [SettingController::class, 'updateLogo'])->name('admin.settings.logo');
    Route::post('/banners', [SettingController::class, 'storeBanner'])->name('admin.banners.store');
    Route::delete('/banners/{id}', [SettingController::class, 'destroyBanner'])->name('admin.banners.destroy');

    // Route Pengumuman
    Route::resource('announcements', AnnouncementController::class, ['as' => 'admin']);

    // Route Kritik Saran Admin
    Route::get('/feedback', [AdminFeedbackController::class, 'index'])->name('admin.feedback.index');
    Route::delete('/feedback/{id}', [AdminFeedbackController::class, 'destroy'])->name('admin.feedback.destroy');

    // 1. DATABASE PETUGAS
    Route::get('/liturgy/personnels', [LiturgyController::class, 'personnelIndex'])->name('admin.liturgy.personnels');
    Route::get('/liturgy/personnels/create', [LiturgyController::class, 'personnelCreate'])->name('admin.liturgy.personnels.create');
    Route::post('/liturgy/personnels', [LiturgyController::class, 'personnelStore'])->name('admin.liturgy.personnels.store');

    // 2. KELOLA JADWAL
    Route::get('/liturgy/schedules', [LiturgyController::class, 'scheduleIndex'])->name('admin.liturgy.schedules');
    Route::get('/liturgy/schedules/create', [LiturgyController::class, 'scheduleCreate'])->name('admin.liturgy.schedules.create');
    Route::post('/liturgy/schedules', [LiturgyController::class, 'scheduleStore'])->name('admin.liturgy.schedules.store');
    
    // 3. ASSIGN (Penugasan)
    Route::get('/liturgy/schedules/{id}/assign', [LiturgyController::class, 'scheduleEdit'])->name('admin.liturgy.assign');
    Route::post('/liturgy/schedules/{id}/assign', [LiturgyController::class, 'assignmentStore'])->name('admin.liturgy.assign.store');
    Route::delete('/liturgy/assignments/{id}', [LiturgyController::class, 'assignmentDestroy'])->name('admin.liturgy.assign.destroy');

     // ROUTE PROFILE SETTING
    Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('admin.profile.password');
});