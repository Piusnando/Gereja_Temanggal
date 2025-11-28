<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Auth\LoginController; // <-- Tambahkan ini

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
Route::get('/teritorial', [PageController::class, 'teritorial']);
Route::get('/organisasi', [PageController::class, 'organisasi']);


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
    
});