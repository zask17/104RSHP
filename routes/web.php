<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\PetController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Dokter\DashboardDokterController;
use App\Http\Controllers\Perawat\DashboardPerawatController;
use App\Http\Controllers\Resepsionis\DashboardResepsionisController;
use App\Http\Controllers\Pemilik\DashboardPemilikController;
use App\Http\Controllers\Resepsionis\PendaftaranController;
use App\Http\Controllers\Pemilik\PeliharaanController;

// --- FIX: Tambahkan semua Controller Admin yang digunakan ---
use App\Http\Controllers\Admin\DataMasterController; // <-- FIX
use App\Http\Controllers\Admin\JenisHewanController; // <-- FIX
use App\Http\Controllers\Admin\RasHewanController; // <-- FIX
use App\Http\Controllers\Admin\KategoriController; // <-- FIX
use App\Http\Controllers\Admin\KategoriKlinisController; // <-- FIX
use App\Http\Controllers\Admin\PemilikController as AdminPemilikController; // <-- FIX (Di-alias karena PemilikController sudah ada di atas)
use App\Http\Controllers\Admin\KodeTindakanTerapiController; // <-- FIX
//-------------------------------------------------------------

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

// LOGOUT
Route::post('/logout', [SiteController::class, 'logout'])->name('logout');

// --- RUTE HALAMAN AUTH ---
Auth::routes();

// AKSES ADMIN
Route::middleware('isAdministrator')->group(function () {
    Route::get('/admin/dashboard', [DashboardAdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/datamaster', [DataMasterController::class, 'index'])->name('admin.datamaster');

    //JENIS HEWAN
    Route::get('/jenis-hewan.index', [JenisHewanController::class, 'index'])->name('admin.jenis-hewan.index');
    Route::get('/jenis-hewan/create', [JenisHewanController::class, 'create'])->name('admin.jenis-hewan.create');
    Route::post('/jenis-hewan', [JenisHewanController::class, 'store'])->name('admin.jenis-hewan.store');
    Route::get('/jenis-hewan/{jenisHewan}/edit', [JenisHewanController::class, 'edit'])->name('admin.jenis-hewan.edit');
    Route::put('/jenis-hewan/{jenisHewan}', [JenisHewanController::class, 'update'])->name('admin.jenis-hewan.update');
    Route::delete('/jenis-hewan/{jenisHewan}', [JenisHewanController::class, 'destroy'])->name('addmin.jenis-hewan.destroy');
    Route::resource('jenis-hewan', JenisHewanController::class)
        ->except(['show'])
        ->parameters([
            'jenis-hewan' => 'jenisHewan'
        ])->names('admin.jenis-hewan');;

    //RAS HEWAN
    Route::get('/ras-hewan.index', [RasHewanController::class, 'index'])->name('admin.ras-hewan.index');
    // Tambahkan Resource Route untuk Ras Hewan
    Route::resource('ras-hewan', RasHewanController::class)->except(['create']);
    // Route::get('/ras-hewan/create', [RasHewanController::class, 'create'])->name('admin.ras-hewan.create');
    Route::post('/ras-hewan', [RasHewanController::class, 'store'])->name('admin.ras-hewan.store');
    Route::get('/ras-hewan/{rasHewan}/edit', [RasHewanController::class, 'edit'])->name('admin.ras-hewan.edit');
    Route::put('/ras-hewan/{rasHewan}', [RasHewanController::class, 'update'])->name('admin.ras-hewan.update');
    Route::delete('/ras-hewan/{rasHewan}', [RasHewanController::class, 'destroy'])->name('admin.ras-hewan.destroy');
    Route::resource('ras-hewan', RasHewanController::class)
        ->except(['show'])
        ->parameters([
            'ras-hewan' => 'rasHewan'
        ])->names('admin.ras-hewan');

    // KATEGORI
    Route::get('/kategori', [KategoriController::class, 'index'])->name('admin.kategori.index');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('admin.kategori.create');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('admin.kategori.store');
    Route::get('/kategori/{kategoriHewan}/edit', [KategoriController::class, 'edit'])->name('admin.kategori.edit');
    Route::put('/kategori/{kategoriHewan}', [KategoriController::class, 'update'])->name('admin.kategori.update');
    Route::delete('/kategori/{kategoriHewan}', [KategoriController::class, 'destroy'])->name('admin.kategori.destroy');
    Route::resource('kategori', KategoriController::class)
        ->except(['show'])
        ->parameters([
            'kategori' => 'kategori'
        ])->names('admin.kategori');;

    // KATEGORI KLINIS
    Route::get('/kategori-klinis', [KategoriKlinisController::class, 'index'])->name('admin.kategori_klinis.index');
    Route::get('/kategori-klinis/create', [KategoriKlinisController::class, 'create'])->name('admin.kategori-klinis.create');
    Route::post('/kategori-klinis', [KategoriKlinisController::class, 'store'])->name('admin.kategori-klinis.store');
    Route::get('/kategori-klinis/{kategoriKlinis}/edit', [KategoriKlinisController::class, 'edit'])->name('admin.kategori-klinis.edit');
    Route::put('/kategori-klinis/{kategoriKlinis}', [KategoriKlinisController::class, 'update'])->name('admin.kategori-klinis.update');
    Route::resource('kategori-klinis', KategoriKlinisController::class)
        ->except(['show'])
        ->parameters([
            'kategori-klinis' => 'kategoriKlinis'
        ])->names('admin.kategori-klinis');;

    // PEMILIK
    Route::get('/pemilik', [AdminPemilikController::class, 'index'])->name('admin.pemilik.index');
    Route::get('/pemilik/create', [AdminPemilikController::class, 'create'])->name('admin.pemilik.create');
    Route::post('/pemilik', [AdminPemilikController::class, 'store'])->name('admin.pemilik.store');
    Route::get('/pemilik/{pemilik}/edit', [AdminPemilikController::class, 'edit'])->name('admin.pemilik.edit');
    Route::put('/pemilik/{pemilik}', [AdminPemilikController::class, 'update'])->name('admin.pemilik.update');
    Route::resource('pemilik', AdminPemilikController::class)
        ->except(['show'])
        ->parameters([
            'pemilik' => 'pemilik'
        ])->names('admin.pemilik');

    // KODE TINDAKAN TERAPI
    Route::get('/kode-tindakan-terapi', [KodeTindakanTerapiController::class, 'index'])->name('admin.kode_tindakan_terapi.index');
    Route::get('/kode-tindakan-terapi/create', [KodeTindakanTerapiController::class, 'create'])->name('admin.kode-tindakan-terapi.create');
    Route::post('/kode-tindakan-terapi', [KodeTindakanTerapiController::class, 'store'])->name('admin.kode-tindakan-terapi.store');
    Route::get('/kode-tindakan-terapi/{kodeTindakanTerapi}/edit', [KodeTindakanTerapiController::class, 'edit'])->name('admin.kode-tindakan-terapi.edit');
    Route::put('/kode-tindakan-terapi/{kodeTindakanTerapi}', [KodeTindakanTerapiController::class, 'update'])->name('admin.kode-tindakan-terapi.update');
    Route::resource('kode-tindakan-terapi', KodeTindakanTerapiController::class)
        ->except(['show'])
        ->parameters([
            'kode-tindakan-terapi' => 'kodeTindakanTerapi'
        ])->names('admin.kode-tindakan-terapi');

    // USER MANAGEMENT
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::resource('users', UserController::class)
        ->except(['show'])
        ->parameters([
            'users' => 'user'
        ])->names('admin.users');

    // ROLE MANAGEMENT
    Route::get('/roles', [RoleController::class, 'index'])->name('admin.roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('admin.roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('admin.roles.store');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('admin.roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
    Route::resource('roles', RoleController::class)
        ->except(['show'])
        ->parameters([
            'roles' => 'role' // Model binding name
        ])->names('admin.roles');

    // PETS (PASIEN)
    Route::get('/pets', [PetController::class, 'index'])->name('admin.pets.index');
    Route::resource('pets', PetController::class)
        ->except(['show'])
        ->parameters([
            'pets' => 'pet' // Model binding name
        ])->names('admin.pets');;
});

//akses Dokter
Route::middleware(['isDokter'])->group(function () {
    Route::get('/dokter/dashboard', [DashboardDokterController::class, 'index'])->name('dokter.dashboard');
});

//akses Perawat
Route::middleware(['isPerawat'])->group(function () {
    Route::get('/perawat/dashboard', [DashboardPerawatController::class, 'index'])->name('perawat.dashboard');
});

// AKSES RESEPSIONIS
Route::middleware(['isResepsionis'])->group(function () {
    Route::get('/resepsionis/dashboard', [DashboardResepsionisController::class, 'index'])->name('resepsionis.dashboard');
    Route::get('/resepsionis/pendaftaran', [PendaftaranController::class, 'index'])->name('resepsionis.pendaftaran');
});

//akses Pemilik
Route::middleware(['isPemilik'])->group(function () {
    Route::get('/pemilik/dashboard', [DashboardPemilikController::class, 'index'])->name('pemilik.dashboard');
    Route::get('/pemilik/pets', [PeliharaanController::class, 'index'])->name('pemilik.pets.index');
});
