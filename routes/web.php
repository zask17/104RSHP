<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Site\SiteController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// CEK KONEKSI

Route::get('/cek-koneksi', [SiteController::class, 'cekKoneksi'])->name('site.cek-koneksi');



// --- RUTE HALAMAN AWAL ---

Route::get('/', [SiteController::class, 'home'])->name('home');

Route::get('/visi-misi', [SiteController::class, 'visiMisi'])->name('visi-misi');

Route::get('/layanan-rshp', [SiteController::class, 'layananRshp'])->name('layanan-rshp');

Route::get('/struktur-organisasi', [SiteController::class, 'strukturOrganisasi'])->name('struktur-organisasi');



// --- RUTE HALAMAN LOGIN ---

// LOGIN
Route::get('/login', [SiteController::class, 'showLogin'])->name('login');
Route::post('/login', [SiteController::class, 'login'])->name('login.process');

// // REGISTER
// Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
// Route::post('/register', [RegisterController::class, 'register'])->name('register.process');


// LOGOUT
Route::post('/logout', [SiteController::class, 'logout'])->name('logout');



// --- RUTE HALAMAN AUTH ---

Auth::routes();


// AKSES ADMIN
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('isAdministrator')->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardAdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/datamaster', [App\Http\Controllers\Admin\DataMasterController::class, 'index'])->name('admin.datamaster');
    Route::get('/admin/jenis-hewan', [App\Http\Controllers\Admin\JenisHewanController::class, 'index'])->name('admin.jenis-hewan.index');
    Route::get('/admin/pemilik', [App\Http\Controllers\Admin\PemilikController::class, 'index'])->name('admin.pemilik.index');
});


// AKSES RESEPSIONIS
Route::middleware('isResepionis')->group(function() {
    Route::get('/resepsionis/dashboard', [App\Http\Controllers\Resepsionis\DashboardResepsionisController::class, 'index'])->name('resepsionis.dashboard');
});