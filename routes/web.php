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

use App\Http\Controllers\Perawat\DashboardPerawatController;

// --- Controller Dokter yang digunakan ---
use App\Http\Controllers\Dokter\DashboardDokterController;
use App\Http\Controllers\Dokter\RekamMedisController;
use App\Http\Controllers\Dokter\DetailRekamMedisController;
use App\Http\Controllers\Dokter\ProfileController;
use App\Http\Controllers\Dokter\PetController as DokterPetController;

// --- Controller Resepsionis yang digunakan ---
use App\Http\Controllers\Resepsionis\DashboardResepsionisController;
use App\Http\Controllers\Resepsionis\PendaftaranController;
use App\Http\Controllers\Resepsionis\PetController as ResepsionisPetController;
use App\Http\Controllers\Resepsionis\PemilikController as ResepsionisPemilikController;
use App\Http\Controllers\Resepsionis\TemuDokterController;

// --- Controller Pemilik yang digunakan ---
use App\Http\Controllers\Pemilik\DashboardPemilikController;
use App\Http\Controllers\Pemilik\PeliharaanController;

// --- Controller Admin yang digunakan ---
use App\Http\Controllers\Admin\DataMasterController;
use App\Http\Controllers\Admin\JenisHewanController;
use App\Http\Controllers\Admin\RasHewanController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\KategoriKlinisController;
use App\Http\Controllers\Admin\PemilikController as AdminPemilikController;
use App\Http\Controllers\Admin\KodeTindakanTerapiController;
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
    Route::get('/jenis-hewan.index', [JenisHewanController::class, 'index'])->name('admin.jenis-hewan');
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
    Route::get('/pemilik', [AdminPemilikController::class, 'index'])->name('admin.pemilik');
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

// AKSES DOKTER
Route::middleware(['isDokter'])->group(function () {
    Route::get('/dokter/dashboard', [DashboardDokterController::class, 'index'])->name('dokter.dashboard');

    // 1. DATA PASIEN (Pet)
    Route::get('/dokter/pasien', [DokterPetController::class, 'index'])->name('dokter.pasien.index');
    Route::get('/dokter/pasien/{id}/show', [DokterPetController::class, 'show'])->name('dokter.pasien.show');

    // 2. REKAM MEDIS & JANJI TEMU
    Route::get('/dokter/rekam-medis', [RekamMedisController::class, 'index'])->name('dokter.rekam-medis.index');
    Route::get('/dokter/rekam-medis/create/{id}', [RekamMedisController::class, 'create'])->name('dokter.rekam-medis.create');
    Route::post('/dokter/rekam-medis/store', [RekamMedisController::class, 'store'])->name('dokter.rekam-medis.store');
    Route::get('/dokter/rekam-medis/{id}/show', [RekamMedisController::class, 'show'])->name('dokter.rekam-medis.show');

    // 3. CRUD DETAIL REKAM MEDIS (Tindakan/Terapi)
    Route::post('/dokter/detail-rekam-medis/store/{idrekam_medis}', [DetailRekamMedisController::class, 'store'])->name('dokter.detail-rekam-medis.store');
    Route::delete('/dokter/detail-rekam-medis/{id}', [DetailRekamMedisController::class, 'destroy'])->name('dokter.detail-rekam-medis.destroy');
    Route::put('/dokter/detail-rekam-medis/{id}', [DetailRekamMedisController::class, 'update'])->name('dokter.detail-rekam-medis.update');

    // 4. PROFIL DOKTER
    Route::get('/dokter/profile', [ProfileController::class, 'index'])->name('dokter.profile.index');
    Route::get('/dokter/profile/edit', [ProfileController::class, 'edit'])->name('dokter.profile.edit');
    Route::put('/dokter/profile/update', [ProfileController::class, 'update'])->name('dokter.profile.update');
});


// AKSES RESEPSIONIS
Route::middleware(['isResepsionis'])->group(function () {
    Route::get('/resepsionis/dashboard', [DashboardResepsionisController::class, 'index'])->name('resepsionis.dashboard');

    // --- FITUR DROPDOWN RAS (Letakkan di sini) ---
    // Pastikan menggunakan ResepsionisPetController jika itu yang mengelola data pet
    Route::get('/get-ras/{id}', [App\Http\Controllers\Resepsionis\PetController::class, 'getRasByJenis'])->name('get.ras.by.jenis');

    // --- 1. REGISTRASI PASIEN SAAT INI (Walk-in/Antrean Harian) ---
    // Menggunakan PendaftaranController sebagai Resource CRUD untuk registrasi harian
    Route::resource('resepsionis/pendaftaran', PendaftaranController::class)
        ->parameters(['pendaftaran' => 'pendaftaran']) // Model binding tidak perlu, pakai ID
        ->names('resepsionis.pendaftaran');

    // --- 2. MANAJEMEN JANJI TEMU (Appointments) ---
    // Menggunakan TemuDokterController sebagai Resource CRUD untuk janji temu non-harian
    Route::resource('resepsionis/temu-dokter', TemuDokterController::class)
        ->parameters(['temu-dokter' => 'temuDokter'])
        ->names('resepsionis.temu-dokter');

    // Rute Resource untuk Pasien (Pets)
    Route::resource('resepsionis/pets', ResepsionisPetController::class)
        ->parameters(['pets' => 'pet'])
        ->names('resepsionis.pets');

    // Route untuk AJAX Ras Hewan (WAJIB ADA)
    Route::get('/get-ras/{id}', [PetController::class, 'getRasByJenis'])->name('get.ras.by.jenis');

    // Rute Resource untuk Pemilik
    Route::resource('resepsionis/pemilik', ResepsionisPemilikController::class)
        ->parameters(['pemilik' => 'pemilik'])
        ->names('resepsionis.pemilik');
});


//AKSES PERAWAT
Route::middleware(['isPerawat'])->group(function () {
    Route::get('/perawat/dashboard', [DashboardPerawatController::class, 'index'])->name('perawat.dashboard');
    Route::delete('/perawat/rekam-medis/{id}', [DashboardPerawatController::class, 'destroyRekamMedis'])->name('perawat.rm.destroy');

    // 3. PROFIL PERAWAT
    Route::get('/perawat/profile', [App\Http\Controllers\Perawat\ProfileController::class, 'index'])->name('perawat.profile.index');
    Route::get('/perawat/profile/edit', [App\Http\Controllers\Perawat\ProfileController::class, 'edit'])->name('perawat.profile.edit');
    Route::put('/perawat/profile/update', [App\Http\Controllers\Perawat\ProfileController::class, 'update'])->name('perawat.profile.update');
});


// AKSES PEMILIK
Route::middleware(['isPemilik'])->group(function () {
    Route::get('/pemilik/dashboard', [DashboardPemilikController::class, 'index'])->name('pemilik.dashboard');
    Route::get('/pemilik/rekam-medis/{id}', [DashboardPemilikController::class, 'detailRekamMedis'])->name('pemilik.rm.detail');
});
