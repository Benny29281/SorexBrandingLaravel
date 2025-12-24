<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// --- 1. Panggil Controller Admin Dashboard (PENTING!) ---
use App\Http\Controllers\Admin\DashboardController;

// --- 2. Panggil Controller Upload ---
use App\Http\Controllers\BrandingRequestController;
use App\Http\Controllers\BrandingRequest2Controller;

use App\Http\Controllers\Admin\UserController; // Pastikan controller di-use

use App\Http\Controllers\BrandingStatusController;
use App\Http\Controllers\Admin\ReportController;

use App\Http\Controllers\UserRequestController;



Route::get('/', function () {
    return view('welcome');
});

// Dashboard User Biasa (Default Laravel)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// 1. Route Dashboard Admin
// Harus mengarah ke [DashboardController::class, 'index'] agar data Total terbaca
Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('admin.dashboard');

// Route untuk melihat Tabel Data 1 (JT, DK, LP)
Route::get('/admin/data-branding-1', [BrandingRequestController::class, 'indexData1'])->name('data.branding1');

// Route untuk melihat Tabel Data 2 (JB, JR)
Route::get('/admin/data-branding-2', [BrandingRequest2Controller::class, 'indexData2'])->name('data.branding2');

// 1. Upload Data JT, DK, LP (Tabel 1)
// Nama rute harus 'import.branding' agar sesuai dengan View
Route::post('/import-branding', [BrandingRequestController::class, 'import'])
    ->name('import.branding');

// 2. Upload Data JB, JR (Tabel 2)
// Nama rute harus 'import.branding2' agar sesuai dengan View
Route::post('/import-branding-2', [BrandingRequest2Controller::class, 'import2'])
    ->name('import.branding2');


Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    // ... route dashboard yang lain ...

    // Route untuk Halaman Register User Baru
    Route::get('/register-user', [UserController::class, 'create'])->name('admin.register');
    
    // Route untuk Proses Simpan User
    Route::post('/register-user', [UserController::class, 'store'])->name('admin.register.store');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    // ... route dashboard lainnya ...

    // 1. LIHAT LIST USER (Riwayat)
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');

    // 2. FORM REGISTER (Yang sudah ada sebelumnya)
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.register');
    Route::post('/users/store', [UserController::class, 'store'])->name('admin.register.store');

    // 3. EDIT USER
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');

    // 4. HAPUS USER
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});


Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    // ... route lainnya ...

    // ROUTE HALAMAN REQUEST BRANDING
    Route::get('/request-branding', [BrandingRequestController::class, 'indexRequest'])->name('admin.request.branding');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    // ... route lainnya ...

    // ROUTE HALAMAN REQUEST BRANDING
    Route::get('/request-branding', [BrandingRequest2Controller::class, 'indexRequest'])->name('admin.request.branding');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    
    // ... route lainnya ...

    // Route ini memanggil fungsi indexRequest di BrandingRequestController
    Route::get('/request-branding', [BrandingRequestController::class, 'indexRequest'])
        ->name('admin.request.branding');

    Route::get('/request-branding', [BrandingRequest2Controller::class, 'indexRequest'])
        ->name('admin.request.branding');

});

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    
    // ... route lainnya ...

    // ROUTE UTAMA
    Route::get('/request-branding', [BrandingRequestController::class, 'indexRequest'])->name('admin.request.branding');

    // ROUTE DELETE (Menghapus Data)
    Route::delete('/request-branding/{region}/{id}', [BrandingRequestController::class, 'destroy'])->name('admin.request.destroy');

    // ROUTE EDIT (Halaman Edit - Opsional jika mau dibuatkan nanti)
    Route::get('/request-branding/{region}/{id}/edit', [BrandingRequest2Controller::class, 'edit'])->name('admin.request.edit');
});

Route::post('/request-branding/store-status', [BrandingRequestController::class, 'storeStatus'])
    ->name('admin.request.store_status');

// Route::post('/request-branding/store-status', [BrandingRequest2Controller::class, 'storeStatus'])
//     ->name('admin.request.store_status');

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    // ... route lain ...

    // HALAMAN STATUS BRANDING (REKAP)
    Route::get('/status-branding', [BrandingStatusController::class, 'index'])->name('admin.status.index');
});

Route::post('/admin/status-branding/import', [App\Http\Controllers\BrandingRequestController::class, 'importStatus'])->name('admin.status.import');
Route::post('/admin/status-branding/import', [App\Http\Controllers\BrandingRequest2Controller::class, 'importStatus'])->name('admin.status.import');

// Route untuk Regional 1
Route::get('/status-branding/reg1', [BrandingRequestController::class, 'indexStatus'])
    ->name('admin.status.reg1');

// Route untuk Regional 2
Route::get('/status-branding/reg2', [BrandingRequest2Controller::class, 'indexStatus'])
    ->name('admin.status.reg2');
    
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    // ... route yang lain ...

    // MENU LAPORAN
    Route::get('/laporan', [ReportController::class, 'index'])->name('admin.laporan.index');
    Route::post('/laporan/export', [ReportController::class, 'export'])->name('admin.laporan.export');
});


// ROUTE KHUSUS USER (SALES / STORE)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Halaman Utama User
    Route::get('/home', [UserRequestController::class, 'index'])->name('user.home');
    
    // Proses Simpan Form
    Route::post('/home/store', [UserRequestController::class, 'store'])->name('user.request.store');

});

// --- ROUTE USER BIASA (Masuk ke Home Input) ---
Route::get('/home', [UserRequestController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('user.home');

Route::post('/home/store', [UserRequestController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('user.request.store');

Route::prefix('user')->name('user.')->group(function () {
    
    // 1. Halaman Form Input Baru (Input Data)
    Route::get('/request/create', [UserRequestController::class, 'create'])->name('request.create');
    
    // 2. Proses Simpan Data (Action Form)
    Route::post('/request/store', [UserRequestController::class, 'store'])->name('request.store');

    // 3. Halaman Revisi (Revisi Data)
    Route::get('/request/revisi', [UserRequestController::class, 'revisi'])->name('request.revisi');

    // 4. Proses Tracking (Cari Data)
    Route::get('/request/track', [UserRequestController::class, 'track'])->name('request.track');

});

// Halaman Form Pencarian ID (Sudah ada di controller sebelumnya 'revisi')
Route::get('/request/revisi', [UserRequestController::class, 'revisi'])->name('user.request.revisi');

// Proses Cek ID (POST)
Route::post('/request/revisi/check', [UserRequestController::class, 'checkRevisi'])->name('user.request.check');

// Proses Simpan Perubahan (PUT/POST)
Route::put('/request/revisi/update', [UserRequestController::class, 'updateRevisi'])->name('user.request.update');