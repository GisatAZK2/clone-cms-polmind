<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\V1\NewsController;
use App\Http\Controllers\V1\PopupController;
use App\Http\Controllers\V1\UserController;
use App\Http\Controllers\V1\HeadlineController;
use App\Http\Controllers\V1\DosenController;
use App\Http\Controllers\V1\MitraController;
use App\Http\Controllers\V1\ProdiController;
use App\Http\Controllers\V1\KeunggulanController;
use App\Http\Controllers\V1\ProjectsController;
use App\Http\Controllers\V1\DokumentasiController;
use App\Http\Controllers\V1\SambutanDirekturController;
use App\Http\Controllers\V1\ContentPendaftaranController;
use App\Http\Controllers\V1\ProfilePageController;
use App\Http\Controllers\V1\KeunikanDanKeunggulanController;
use App\Http\Controllers\V1\ProgramSarjanaTerapanController;
use App\Http\Controllers\V1\HomeStatController;
use App\Http\Controllers\V1\PendaftaranMahasiswaBaruController;
use App\Http\Controllers\V1\KarakterController;

// ===== PUBLIC ROUTES =====
// Home/Beranda
Route::get('/', [PageController::class, 'beranda'])->name('beranda');
Route::get('/beranda', [PageController::class, 'beranda'])->name('beranda');

// Profil
Route::get('/profil', [PageController::class, 'profil'])->name('profil');

// Keunikan
Route::get('/keunikan', [PageController::class, 'keunikan'])->name('keunikan');

// PMB (Pendaftaran Mahasiswa Baru)
Route::get('/pmb', [PageController::class, 'pmb'])->name('pmb');

// Program Studi
Route::get('/prodi', [PageController::class, 'prodi'])->name('prodi');

// Daftar Dosen
Route::get('/daftar_dosen', [PageController::class, 'daftar_dosen'])->name('daftar_dosen');


// Dokumentasi
Route::get('/dokumentasi', [PageController::class, 'dokumentasi'])->name('dokumentasi');

// News/Berita - Public Pages
// routes/web.php
Route::get('/berita', [NewsController::class, 'publicIndex'])->name('berita.index');
Route::get('/berita/{slug}', [NewsController::class, 'publicShow'])->name('berita.show');

// Daftar Dosen - Public Page
Route::get('/daftar_dosen', [DosenController::class, 'publicIndex'])->name('daftar.dosen');

// Daftar Tenaga Kependidikan - Public Page
Route::get('/daftar_tendik', [DosenController::class, 'tendik'])->name('daftar.tendik');

// Dokumentasi - Public Page
Route::get('/dokumentasi', [DokumentasiController::class, 'publicIndex'])->name('daftar.dokumentasi');

// Program Sarjana Terapan - Public Page
Route::get('/prodi', [ProgramSarjanaTerapanController::class, 'publicIndex'])->name('daftar.prodi');

// Pendaftaran Mahasiswa Baru - Public Page
Route::get('/pmb', [PendaftaranMahasiswaBaruController::class, 'publicIndex'])->name('pmb');

// Placeholder route untuk berita (backward compatibility)
Route::get('/beranda/berita', function () {
    return redirect(route('berita.index'));
});

// ===== AUTHENTICATION ROUTES =====
Route::prefix('admin')->group(function () {

    Route::get('/login', function () {
        return view('admin.auth.login');
    })->name('login')->middleware('guest');

    Route::post('/login', [PageController::class, 'handleLogin'])
        ->middleware('guest')
        ->name('login.post');

    Route::post('/logout', function () {
        auth()->logout();
        return redirect('/');
    })->middleware('auth')->name('logout');

});

// ===== ADMIN ROUTES (Protected by auth middleware) =====
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Profile
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [AdminController::class, 'updateProfile'])->name('profile.update');

    // News Management
    Route::post('/news/bulk-destroy', [NewsController::class, 'bulkDestroy'])->name('news.bulk-destroy');
    Route::resource('news', NewsController::class, ['names' => 'news']);
    Route::post('/news/reorder', [NewsController::class, 'reorder'])->name('news.reorder');

    Route::get('/setting', function() {
        return view('admin.setting');
        })->name('setting'); 
});



Route::middleware(['auth', 'role:superadmin'])->prefix('admin')->name('admin.')->group(function () {
    // Popup Management
    Route::post('/popup/bulk-destroy', [PopupController::class, 'bulkDestroy'])->name('popup.bulk-destroy');
    Route::resource('popup', PopupController::class, ['names' => 'popup']);

    // Mitra Management
    Route::post('/mitra/bulk-destroy', [MitraController::class, 'bulkDestroy'])->name('mitra.bulk-destroy');
    Route::resource('mitra', MitraController::class, ['names' => 'mitra']);

    // Sambutan Direktur Management
    Route::post('/sambutan-direktur/bulk-destroy', [SambutanDirekturController::class, 'bulkDestroy'])->name('sambutan-direktur.bulk-destroy');
    Route::resource('sambutan-direktur', SambutanDirekturController::class, ['names' => 'sambutan-direktur']);

    // Content Pendaftaran Management
    Route::post('/content-pendaftaran/bulk-destroy', [ContentPendaftaranController::class, 'bulkDestroy'])->name('content-pendaftaran.bulk-destroy');
    Route::resource('content-pendaftaran', ContentPendaftaranController::class, ['names' => 'content-pendaftaran']);

    // Pendaftaran Mahasiswa Baru Management (custom CRUD)
    Route::post('/pendaftaran-mahasiswa-baru/bulk-destroy', [PendaftaranMahasiswaBaruController::class, 'bulkDestroy'])->name('pendaftaran-mahasiswa-baru.bulk-destroy');
    Route::resource('pendaftaran-mahasiswa-baru', PendaftaranMahasiswaBaruController::class, ['names' => 'pendaftaran-mahasiswa-baru']);

    // Statistik Beranda Management
    Route::post('/home-stat/bulk-destroy', [HomeStatController::class, 'bulkDestroy'])->name('home-stat.bulk-destroy');
    Route::resource('home-stat', HomeStatController::class, ['names' => 'home-stat']);

    // Prodi Management
    Route::post('/prodi/bulk-destroy', [ProdiController::class, 'bulkDestroy'])->name('prodi.bulk-destroy');
    Route::resource('prodi', ProdiController::class, ['names' => 'prodi']);

    // Keunggulan Management
    Route::post('/keunggulan/bulk-destroy', [KeunggulanController::class, 'bulkDestroy'])->name('keunggulan.bulk-destroy');
    Route::resource('keunggulan', KeunggulanController::class, ['names' => 'keunggulan']);

    // Karakter Management
    Route::post('/karakter/bulk-destroy', [KarakterController::class, 'bulkDestroy'])->name('karakter.bulk-destroy');
    Route::resource('karakter', KarakterController::class, ['names' => 'karakter']);

    // Projects Management
    Route::post('/projects/bulk-destroy', [ProjectsController::class, 'bulkDestroy'])->name('projects.bulk-destroy');
    Route::resource('projects', ProjectsController::class, ['names' => 'projects']);

    // Dokumentasi Management
    Route::post('/dokumentasi/bulk-destroy', [DokumentasiController::class, 'bulkDestroy'])->name('dokumentasi.bulk-destroy');
    Route::resource('dokumentasi', DokumentasiController::class, ['names' => 'dokumentasi']);

    // Profile Page Management
    Route::post('/profile-page/bulk-destroy', [ProfilePageController::class, 'bulkDestroy'])->name('profile-page.bulk-destroy');
    Route::resource('profile-page', ProfilePageController::class, ['names' => 'profile-page']);

    // Keunikan dan Keunggulan Management
    Route::post('/keunikan-dan-keunggulan/bulk-destroy', [KeunikanDanKeunggulanController::class, 'bulkDestroy'])->name('keunikan-dan-keunggulan.bulk-destroy');
    Route::resource('keunikan-dan-keunggulan', KeunikanDanKeunggulanController::class, ['names' => 'keunikan-dan-keunggulan']);

    // Program Sarjana Terapan Management
    Route::post('/program-sarjana-terapan/bulk-destroy', [ProgramSarjanaTerapanController::class, 'bulkDestroy'])->name('program_sarjana_terapan.bulk-destroy');
    Route::resource('program-sarjana-terapan', ProgramSarjanaTerapanController::class, ['names' => 'program_sarjana_terapan']);

    // User Management
    Route::post('/users/bulk-destroy', [UserController::class, 'bulkDestroy'])->name('users.bulk-destroy');
    Route::resource('users', UserController::class, ['names' => 'users']);

    // Headline Management
    Route::get('/headline', [HeadlineController::class, 'index'])->name('headline.index');
    Route::post('/headline/reorder', [HeadlineController::class, 'reorder'])->name('headline.reorder');
    Route::get('/headline/create', [HeadlineController::class, 'create'])->name('headline.create');
    Route::post('/headline', [HeadlineController::class, 'store'])->name('headline.store');
    Route::post('/headline/bulk-destroy', [HeadlineController::class, 'bulkDestroy'])->name('headline.bulk-destroy');
    Route::get('/headline/{id}/edit', [HeadlineController::class, 'edit'])->name('headline.edit');
    Route::put('/headline/{id}', [HeadlineController::class, 'update'])->name('headline.update');
    Route::delete('/headline/{id}', [HeadlineController::class, 'destroy'])->name('headline.destroy');

    // Dosen Management
    Route::get('/dosen', [DosenController::class, 'index'])->name('dosen.index');
    Route::get('/dosen/create', [DosenController::class, 'create'])->name('dosen.create');
    Route::post('/dosen', [DosenController::class, 'store'])->name('dosen.store');
    Route::get('/dosen/{id}/edit', [DosenController::class, 'edit'])->name('dosen.edit');
    Route::put('/dosen/{id}', [DosenController::class, 'update'])->name('dosen.update');
    Route::post('/dosen/bulk-destroy', [DosenController::class, 'bulkDestroy'])->name('dosen.bulk-destroy');
    Route::delete('/dosen/{id}', [DosenController::class, 'destroy'])->name('dosen.destroy');
});

Route::prefix('admin')
    ->middleware('auth')
    ->name('admin.')
    ->group(function () {
        Route::fallback(function () {
            return response()->view('errors.admin.404', [], 404);
        })->name('fallback');
    });