<?php

use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\FacilityBookingController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\Admin\LiturgyController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\YouthAttendanceController;
use App\Http\Controllers\Admin\YouthDashboardController; // Pastikan Import Ini Ada
use App\Http\Controllers\Admin\YouthMemberController;    // Pastikan Import Ini Ada
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/sejarah', [PageController::class, 'sejarah']);
Route::get('/pengumuman', [PageController::class, 'pengumuman']);
Route::get('/pengumuman/{id}', [PageController::class, 'detailPengumuman'])->name('pengumuman.detail');
Route::get('/kegiatan', [PageController::class, 'kegiatan'])->name('kegiatan.index');
Route::get('/kegiatan/{id}', [PageController::class, 'detailKegiatan'])->name('kegiatan.detail');
Route::get('/jadwal-pemakaian-gedung', [PageController::class, 'jadwalGedung'])->name('jadwal.gedung');
Route::get('/teritorial', [PageController::class, 'teritorial'])->name('teritorial.index');
Route::get('/teritorial/{slug}', [PageController::class, 'showTeritorial'])->name('teritorial.show');
Route::get('/organisasi', [PageController::class, 'organisasi'])->name('organisasi.index');
Route::get('/organisasi/{category}', [PageController::class, 'showOrganization'])->name('organisasi.show');
Route::get('/organisasi/{category}/{sub_category}', [PageController::class, 'showSubOrganization'])->name('organisasi.sub');
Route::get('/jadwal-petugas', [PageController::class, 'jadwalPetugas'])->name('jadwal.petugas');
Route::get('/petugas/{role}', [PageController::class, 'showPetugasRole'])->name('petugas.role');
Route::get('/lingkungan/{id}', [PageController::class, 'detailLingkungan'])->name('lingkungan.detail');

Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->group(function () {

    Route::get('/dashboard', function() { return view('admin.dashboard'); })->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('admin.profile.password');

    // ADMIN SUPER USER
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
        Route::post('/settings/logo', [SettingController::class, 'updateLogo'])->name('admin.settings.logo');
        Route::post('/banners', [SettingController::class, 'storeBanner'])->name('admin.banners.store');
        Route::put('/banners/update-all', [SettingController::class, 'updateAllBanners'])->name('admin.banners.update_all');
        Route::delete('/banners/{id}', [SettingController::class, 'destroyBanner'])->name('admin.banners.destroy');
        Route::resource('users', UserController::class, ['as' => 'admin']);
    });

    // FEEDBACK & FACILITY & LINGKUNGAN
    Route::middleware(['role:admin,pengurus_gereja'])->group(function () {
        Route::get('/feedback', [AdminFeedbackController::class, 'index'])->name('admin.feedback.index');
        Route::delete('/feedback/{id}', [AdminFeedbackController::class, 'destroy'])->name('admin.feedback.destroy');
        Route::resource('facility-bookings', FacilityBookingController::class, ['as' => 'admin']);
        Route::resource('lingkungan', \App\Http\Controllers\Admin\LingkunganController::class, ['as' => 'admin']);
    });

    // LITURGI
    Route::middleware(['role:admin,direktur_musik,misdinar,lektor,pengurus_gereja'])->group(function () {
        Route::get('/liturgy/personnels', [LiturgyController::class, 'personnelIndex'])->name('admin.liturgy.personnels');
        Route::get('/liturgy/personnels/create', [LiturgyController::class, 'personnelCreate'])->name('admin.liturgy.personnels.create');
        Route::post('/liturgy/personnels', [LiturgyController::class, 'personnelStore'])->name('admin.liturgy.personnels.store');
        Route::get('/liturgy/schedules', [LiturgyController::class, 'scheduleIndex'])->name('admin.liturgy.schedules');

        Route::middleware(['role:admin,pengurus_gereja,direktur_musik'])->group(function() {
            Route::get('/liturgy/schedules/create', [LiturgyController::class, 'scheduleCreate'])->name('admin.liturgy.schedules.create');
            Route::post('/liturgy/schedules', [LiturgyController::class, 'scheduleStore'])->name('admin.liturgy.schedules.store');
            Route::get('/liturgy/schedules/{id}/edit', [LiturgyController::class, 'editSchedule'])->name('admin.liturgy.schedules.edit');
            Route::put('/liturgy/schedules/{id}', [LiturgyController::class, 'updateSchedule'])->name('admin.liturgy.schedules.update');
            Route::delete('/liturgy/schedules/{id}', [LiturgyController::class, 'destroySchedule'])->name('admin.liturgy.schedules.destroy');
        });
        
        Route::middleware(['role:admin,pengurus_gereja,direktur_musik,misdinar,lektor'])->group(function() {
            Route::get('/liturgy/schedules/{id}/assign', [LiturgyController::class, 'scheduleEdit'])->name('admin.liturgy.assign');
            Route::post('/liturgy/schedules/{id}/assign', [LiturgyController::class, 'assignmentStore'])->name('admin.liturgy.assign.store');
            Route::delete('/liturgy/assignments/{id}', [LiturgyController::class, 'assignmentDestroy'])->name('admin.liturgy.assign.destroy');
        });

        Route::delete('/liturgy/personnels/{id}', [LiturgyController::class, 'personnelDestroy'])->name('admin.liturgy.personnels.destroy');
    });

    // ORGANISASI, KONTEN & YOUTH
    Route::middleware(['role:admin,pengurus_gereja,omk,misdinar,lektor,direktur_musik,pia_pir'])->group(function () {
        
        // 1. KONTEN & YOUTH (OMK/PIA/PIR Boleh Akses)
        Route::middleware(['role:admin,pengurus_gereja,omk,pia_pir'])->group(function () {
            Route::resource('announcements', AnnouncementController::class, ['as' => 'admin']);
            // TAMBAHAN: Activity (PENTING AGAR TIDAK ERROR)
            Route::resource('activities', ActivityController::class, ['as' => 'admin']);
            
            // DASHBOARD YOUTH
            Route::get('/youth-dashboard', [YouthDashboardController::class, 'index'])->name('admin.youth.dashboard');
            
            // CRUD ANGGOTA YOUTH
            Route::prefix('youth/members')->name('admin.youth.members.')->group(function() {
                Route::get('/', [YouthMemberController::class, 'index'])->name('index');
                Route::get('/create', [YouthMemberController::class, 'create'])->name('create');
                Route::post('/', [YouthMemberController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [YouthMemberController::class, 'edit'])->name('edit');
                Route::put('/{id}', [YouthMemberController::class, 'update'])->name('update');
                Route::delete('/{id}', [YouthMemberController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('youth/attendance')->name('admin.youth.attendance.')->group(function() {
                Route::get('/', [YouthAttendanceController::class, 'create'])->name('create');
                Route::post('/', [YouthAttendanceController::class, 'store'])->name('store');
                Route::resource('events', \App\Http\Controllers\Admin\YouthEventController::class)->names('events');
            });
        });
        
        // 2. STRUKTUR ORGANISASI
        Route::get('/organization', [OrganizationController::class, 'index'])->name('admin.organization.index');
        Route::get('/organization/create', [OrganizationController::class, 'create'])->name('admin.organization.create');
        Route::post('/organization', [OrganizationController::class, 'store'])->name('admin.organization.store');
        Route::delete('/organization/{id}', [OrganizationController::class, 'destroy'])->name('admin.organization.destroy');
        Route::get('/organization/{id}/edit', [OrganizationController::class, 'edit'])->name('admin.organization.edit');
        Route::put('/organization/{id}', [OrganizationController::class, 'update'])->name('admin.organization.update');
        Route::post('/organization/reorder', [OrganizationController::class, 'reorder'])->name('admin.organization.reorder');
    });
});