<?php

use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\FacilityBookingController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\Admin\InvCategoryController;
use App\Http\Controllers\Admin\InvDashboardController;
use App\Http\Controllers\Admin\InvLocationController;
use App\Http\Controllers\Admin\LingkunganController; // Pastikan ini di-import
use App\Http\Controllers\Admin\LiturgyController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FeedbackController; 
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\InvItemController;


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

    // 1. RUTE UMUM (Bisa diakses semua role yang login)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('admin.profile.password');

    // 2. LOGIKA PEMILIHAN & DASHBOARD
    Route::get('/dashboard', function() { 
        if (Auth::user()->role === 'inventaris') {
            return redirect()->route('admin.inventaris.dashboard');
        }
        return view('admin.dashboard'); 
    })->name('dashboard');

    Route::get('/choose-dashboard', function() {
        if(Auth::user()->role !== 'admin') return redirect()->route('dashboard');
        return view('admin.choose_dashboard');
    })->name('admin.choose_dashboard');

    // 3. RUTE INVENTARIS (HANYA UNTUK ADMIN & INVENTARIS)
    Route::middleware(['role:admin,inventaris'])->prefix('inventaris')->name('admin.inventaris.')->group(function () {
        Route::get('/', [InvDashboardController::class, 'index'])->name('dashboard');
        Route::get('/chart-data', [InvDashboardController::class, 'getDataForCharts'])->name('chart_data');
        Route::resource('locations', InvLocationController::class);
        Route::resource('categories', InvCategoryController::class);
        Route::get('items/export', [\App\Http\Controllers\Admin\InvItemController::class, 'export'])->name('items.export');
        Route::resource('items', InvItemController::class);
    });

    // 4. KHUSUS ADMIN SUPER USER (Kelola User)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class, ['as' => 'admin']);
    });

    // 4.5. PENGATURAN TAMPILAN (Admin & Koster)
    Route::middleware(['role:admin,koster'])->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
        Route::post('/settings/logo', [SettingController::class, 'updateLogo'])->name('admin.settings.logo');
        Route::post('/banners', [SettingController::class, 'storeBanner'])->name('admin.banners.store');
        Route::put('/banners/update-all', [SettingController::class, 'updateAllBanners'])->name('admin.banners.update_all');
        Route::delete('/banners/{id}', [SettingController::class, 'destroyBanner'])->name('admin.banners.destroy');
    });

    // 5. ADMIN, PENGURUS GEREJA, & KOSTER
    Route::middleware(['role:admin,pengurus_gereja,koster'])->group(function () {
        Route::get('/feedback', [AdminFeedbackController::class, 'index'])->name('admin.feedback.index');
        Route::delete('/feedback/{id}', [AdminFeedbackController::class, 'destroy'])->name('admin.feedback.destroy');
        Route::resource('facility-bookings', FacilityBookingController::class, ['as' => 'admin']);
    });

    // 5.5. ADMIN & PENGURUS GEREJA (Data Teritorial)
    Route::middleware(['role:admin,pengurus_gereja'])->group(function () {
        Route::resource('lingkungan', LingkunganController::class, ['as' => 'admin']);
    });

    // 6. ZONA LITURGI
    Route::middleware(['role:admin,direktur_musik,misdinar,lektor,pengurus_gereja,koster'])->group(function () {
        Route::get('/liturgy/personnels', [LiturgyController::class, 'personnelIndex'])->name('admin.liturgy.personnels');
        Route::get('/liturgy/personnels/create', [LiturgyController::class, 'personnelCreate'])->name('admin.liturgy.personnels.create');
        Route::post('/liturgy/personnels', [LiturgyController::class, 'personnelStore'])->name('admin.liturgy.personnels.store');
        Route::get('/liturgy/schedules', [LiturgyController::class, 'scheduleIndex'])->name('admin.liturgy.schedules');
        Route::delete('/liturgy/personnels/{id}', [LiturgyController::class, 'personnelDestroy'])->name('admin.liturgy.personnels.destroy');

        // CRUD Jadwal Misa
        Route::middleware(['role:admin,pengurus_gereja,direktur_musik,koster'])->group(function() {
            Route::get('/liturgy/schedules/create', [LiturgyController::class, 'scheduleCreate'])->name('admin.liturgy.schedules.create');
            Route::post('/liturgy/schedules', [LiturgyController::class, 'scheduleStore'])->name('admin.liturgy.schedules.store');
            Route::get('/liturgy/schedules/{id}/edit', [LiturgyController::class, 'editSchedule'])->name('admin.liturgy.schedules.edit');
            Route::put('/liturgy/schedules/{id}', [LiturgyController::class, 'updateSchedule'])->name('admin.liturgy.schedules.update');
            Route::delete('/liturgy/schedules/{id}', [LiturgyController::class, 'destroySchedule'])->name('admin.liturgy.schedules.destroy');
        });
        
        // Assign Petugas
        Route::middleware(['role:admin,pengurus_gereja,direktur_musik,misdinar,lektor,koster'])->group(function() {
            Route::get('/liturgy/schedules/{id}/assign', [LiturgyController::class, 'scheduleEdit'])->name('admin.liturgy.assign');
            Route::post('/liturgy/schedules/{id}/assign', [LiturgyController::class, 'assignmentStore'])->name('admin.liturgy.assign.store');
            Route::delete('/liturgy/assignments/{id}', [LiturgyController::class, 'assignmentDestroy'])->name('admin.liturgy.assign.destroy');
        });
    });

    // 7. KONTEN & INFORMASI (Pengumuman, Kegiatan)
    Route::middleware(['role:admin,pengurus_gereja,omk,pia_pir,koster'])->group(function () {
        Route::resource('announcements', AnnouncementController::class, ['as' => 'admin']);
        Route::resource('activities', ActivityController::class, ['as' => 'admin']);
    });

    // 8. ORGANISASI (Koster tidak diberi akses ke sini)
    Route::middleware(['role:admin,pengurus_gereja,omk,misdinar,lektor,direktur_musik,pia_pir'])->group(function () {
        Route::get('/organization', [OrganizationController::class, 'index'])->name('admin.organization.index');
        Route::get('/organization/create', [OrganizationController::class, 'create'])->name('admin.organization.create');
        Route::post('/organization', [OrganizationController::class, 'store'])->name('admin.organization.store');
        Route::delete('/organization/{id}', [OrganizationController::class, 'destroy'])->name('admin.organization.destroy');
        Route::get('/organization/{id}/edit', [OrganizationController::class, 'edit'])->name('admin.organization.edit');
        Route::put('/organization/{id}', [OrganizationController::class, 'update'])->name('admin.organization.update');
        Route::post('/organization/reorder', [OrganizationController::class, 'reorder'])->name('admin.organization.reorder');
        Route::post('/organization/reorder-teams', [OrganizationController::class, 'reorderTeams'])->name('admin.organization.reorder_teams');
    });

    // BINA IMAN (OMK & PIA/PIR) - Dipisah berdasarkan URL {category}
    Route::middleware(['role:admin,pengurus_gereja,omk,pia_pir'])->prefix('youth/{category}')->name('admin.youth.')->group(function () {
        Route::get('/members', [\App\Http\Controllers\Admin\YouthController::class, 'membersIndex'])->name('members');
        Route::post('/members',[\App\Http\Controllers\Admin\YouthController::class, 'memberStore'])->name('members.store');
        Route::delete('/members/{id}',[\App\Http\Controllers\Admin\YouthController::class, 'memberDestroy'])->name('members.destroy');
        
        Route::get('/members/{id}/edit',[\App\Http\Controllers\Admin\YouthController::class, 'memberEdit'])->name('members.edit');
        Route::put('/members/{id}', [\App\Http\Controllers\Admin\YouthController::class, 'memberUpdate'])->name('members.update');

        Route::get('/events', [\App\Http\Controllers\Admin\YouthController::class, 'eventsIndex'])->name('events');
        Route::post('/events',[\App\Http\Controllers\Admin\YouthController::class, 'eventStore'])->name('events.store');
        
        Route::get('/events/{id}/attendance', [\App\Http\Controllers\Admin\YouthController::class, 'attendanceShow'])->name('attendance');
        Route::post('/events/{id}/attendance',[\App\Http\Controllers\Admin\YouthController::class, 'attendanceStore'])->name('attendance.store');
    });
    
});