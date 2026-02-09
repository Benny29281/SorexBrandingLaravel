<?php

use Illuminate\Support\Facades\Route;

// --- CONTROLLER IMPORTS ---
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\MasterDataController; // Controller Master Data
use App\Http\Controllers\NotificationController;

// Controller Regional 1 (JT, DK, LP)
use App\Http\Controllers\BrandingRequestController;

// Controller Regional 2 (JB, JR)
use App\Http\Controllers\BrandingRequest2Controller;
use App\Http\Controllers\BrandingStatusController;

// Controller User (Sales/Store)
use App\Http\Controllers\UserRequestController;
use App\Http\Controllers\User\HomeController;

use Illuminate\Support\Facades\Storage;


/*
|--------------------------------------------------------------------------
| WEB ROUTES (PUBLIC & AUTH BASIC)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Dashboard Default Laravel (Redirect ke home user/admin nanti di logic controller)// GANTI DENGAN INI:
Route::get('/dashboard', function () {
    $user = auth()->user();
    $role = $user->regional;

    // Daftar yang harus ke Admin Dashboard
    $aksesAdmin = ['Admin', 'Design'];

    if (in_array($role, $aksesAdmin)) {
        return redirect()->route('admin.dashboard');
    }

    // Sisanya (LP, JB, JR, JT, DK) lempar ke Home User
    return redirect()->route('user.home');
    
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile Standard
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth Routes (Login, Register, dll)
require __DIR__.'/auth.php';


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (GROUP)
|--------------------------------------------------------------------------
| Semua route di sini memiliki prefix 'admin' dan middleware 'auth'.
| URL Akses: domain.com/admin/....
*/

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {

    // 1. DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // 2. USER MANAGEMENT (Register & List User)
    // List User
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    // Form Tambah User
    Route::get('/register-user', [UserController::class, 'create'])->name('admin.register');
    Route::post('/register-user', [UserController::class, 'store'])->name('admin.register.store');
    // Edit & Hapus User
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');


    // 3. MASTER DATA (Controller Input Data - Sales, SPV, Tools)
    // Menggunakan Resource agar otomatis mencakup index, create, store, edit, update, destroy
    Route::resource('master', MasterDataController::class)->names([
        'index' => 'admin.master.index',
        'create' => 'admin.master.create',
        'store' => 'admin.master.store',
        'edit' => 'admin.master.edit',
        'update' => 'admin.master.update',
        'destroy' => 'admin.master.destroy',
    ]);


    // 4. DATA BRANDING (TABEL REGIONAL 1 & 2)
    // Lihat Data Reg 1
    Route::get('/data-branding-1', [BrandingRequestController::class, 'indexData1'])->name('data.branding1');
    // Lihat Data Reg 2
    Route::get('/data-branding-2', [BrandingRequest2Controller::class, 'indexData2'])->name('data.branding2');
    
    // Import Data Reg 1
    Route::post('/import-branding', [BrandingRequestController::class, 'import'])->name('import.branding');
    // Import Data Reg 2
    Route::post('/import-branding-2', [BrandingRequest2Controller::class, 'import2'])->name('import.branding2');


    // 5. REQUEST BRANDING (HALAMAN UTAMA)
    // Kita arahkan ke Controller Utama (Reg 1) sebagai default view, nanti di view diload datanya
    Route::get('/request-branding', [BrandingRequestController::class, 'indexRequest'])->name('admin.request.branding');
    
    // Action: Hapus Data Request
    Route::delete('/request-branding/{region}/{id}', [BrandingRequestController::class, 'destroy'])->name('admin.request.destroy');
    // Action: Store Status (Update status ACC/Tolak)
    Route::post('/request-branding/store-status', [BrandingRequestController::class, 'storeStatus'])->name('admin.request.store_status');
    Route::post('/admin/request/status', [BrandingRequest2Controller::class, 'storeStatus'])->name('admin.request.store_status');


    // 6. STATUS BRANDING (REKAP)
    Route::get('/status-branding', [BrandingStatusController::class, 'index'])->name('admin.status.index');
    
    // Import Status (PISAH URL AGAR TIDAK KONFLIK)
    Route::post('/status-branding/import-reg1', [BrandingRequestController::class, 'importStatus'])->name('admin.status.import');
    Route::post('/status-branding/import-reg2', [BrandingRequest2Controller::class, 'importStatus'])->name('admin.status.import2'); // Nama route beda
    
    // Lihat Status per Regional (JSON/Partial)
    Route::get('/status-branding/reg1', [BrandingRequestController::class, 'indexStatus'])->name('admin.status.reg1');
    Route::get('/status-branding/reg2', [BrandingRequest2Controller::class, 'indexStatus'])->name('admin.status.reg2');


    // 7. LAPORAN & EXPORT
    Route::get('/laporan', [ReportController::class, 'index'])->name('admin.laporan.index');
    Route::post('/laporan/export', [ReportController::class, 'export'])->name('admin.laporan.export');

});


/*
|--------------------------------------------------------------------------
| USER ROUTES (SALES / STORE / DESIGN)
|--------------------------------------------------------------------------
*/

Route::prefix('user')->middleware(['auth', 'verified'])->group(function () {
    
    // 1. HOME & DASHBOARD USER
    Route::get('/home', [HomeController::class, 'index'])->name('user.home');

    // 2. FORM INPUT BARU
    Route::get('/request/create', [UserRequestController::class, 'create'])->name('user.request.create');
    Route::post('/request/store', [UserRequestController::class, 'store'])->name('user.request.store');

    // 3. TRACKING & REVISI
    Route::get('/request/track', [UserRequestController::class, 'track'])->name('user.request.track');
    
    // Revisi
    Route::get('/request/revisi', [UserRequestController::class, 'revisi'])->name('user.request.revisi');
    Route::post('/request/revisi/check', [UserRequestController::class, 'checkRevisi'])->name('user.request.check');
    Route::put('/request/revisi/update', [UserRequestController::class, 'updateRevisi'])->name('user.request.update');

    // 4. DOWNLOAD CENTER (Design/User)
    Route::get('/pusat-download', [HomeController::class, 'viewDownloadPage'])->name('user.download.page');
    Route::get('/download-status', [HomeController::class, 'downloadStatus'])->name('user.download.status');

    // 5. UTILITY USER
    Route::get('/log-aktivitas', [UserRequestController::class, 'historyLog'])->name('user.log');
    Route::post('/profile/upload', [UserRequestController::class, 'uploadPhoto'])->name('user.profile.upload');

});


/*
|--------------------------------------------------------------------------
| UTILITY ROUTES (SHARED)
|--------------------------------------------------------------------------
*/

// Notifikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications/all', [NotificationController::class, 'index'])->name('notification.index');
    Route::get('/notification/read/{id}', [NotificationController::class, 'markAsRead'])->name('notification.read');
    Route::get('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notification.read.all');
});

// Refresh CSRF Token (Untuk AJAX)
Route::get('/refresh-csrf', function () {
    return response()->json(['csrf_token' => csrf_token()]);
})->name('refresh.csrf');

Route::get('/cek-drive', function() {
    try {
        // Coba list file di dalam folder tujuan
        $files = Storage::disk('google')->files();
        
        return [
            'status' => 'Koneksi Berhasil!',
            'files_di_folder_ini' => $files
        ];
    } catch (\Exception $e) {
        return ['error' => $e->getMessage()];
    }
});

// use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

Route::get('/cek-drive-final', function () {
    try {
        echo "<h1>🕵️ Detektif Google Drive</h1>";
        
        // 1. Cek Konfigurasi
        $folderId = Config::get('filesystems.disks.google.folderId');
        $jsonPath = Config::get('filesystems.disks.google.serviceAccountCredentials');
        
        echo "<b>1. Konfigurasi:</b><br>";
        echo "• Folder ID Target: <code>{$folderId}</code><br>";
        echo "• Lokasi JSON: <code>{$jsonPath}</code><br>";
        
        // 2. Cek Isi JSON (Mencari Email Robot)
        if (file_exists($jsonPath)) {
            $jsonContent = json_decode(file_get_contents($jsonPath), true);
            $clientEmail = $jsonContent['client_email'] ?? 'Tidak ditemukan';
            echo "• <b>Email Robot (Service Account):</b> <code style='color:red; font-size:1.2em'>{$clientEmail}</code> <br>";
            echo "<i>(⚠️ Pastikan Folder Google Drive Anda sudah di-Share ke email merah di atas sebagai EDITOR!)</i><br><br>";
        } else {
            throw new Exception("File JSON tidak ditemukan di path tersebut!");
        }

        // 3. Tes Tulis File Sederhana
        echo "<b>2. Tes Upload File Teks:</b><br>";
        $fileName = 'tes_koneksi_' . time() . '.txt';
        Storage::disk('google')->put($fileName, 'Halo! Ini tes dari Laravel. Jika Anda membaca ini, koneksi BERHASIL.');
        echo "• Mencoba upload file bernama <code>{$fileName}</code>... ";
        
        // Cek apakah file benar-benar ada via API
        if (Storage::disk('google')->exists($fileName)) {
            echo "<span style='color:green; font-weight:bold'>SUKSES! ✅</span><br>";
            echo "• File berhasil dibuat di root folder.<br>";
        } else {
            echo "<span style='color:red; font-weight:bold'>GAGAL! ❌</span> (File tidak terdeteksi setelah upload)<br>";
        }

        // 4. Tes Buat Folder uploads/branding
        echo "<br><b>3. Tes Folder 'uploads/branding':</b><br>";
        $targetPath = 'uploads/branding/tes_gambar.txt';
        Storage::disk('google')->put($targetPath, 'Tes file di dalam folder.');
        echo "• Mencoba upload ke <code>{$targetPath}</code>... <span style='color:green; font-weight:bold'>OK</span><br>";
        
        // 5. Tampilkan URL
        $url = Storage::disk('google')->url($fileName);
        echo "<br><b>4. URL File:</b> <a href='{$url}' target='_blank'>{$url}</a>";

        echo "<hr><h3>🎉 Kesimpulan:</h3>";
        echo "Jika semua status di atas HIJAU, cek Google Drive folder <code>{$folderId}</code> sekarang. <br>";
        echo "Cari file bernama <b>{$fileName}</b>. Jika tidak ada, berarti Anda salah share folder atau salah Folder ID.";

    } catch (\Exception $e) {
        echo "<h3 style='color:red'>TERJADI ERROR:</h3>";
        echo $e->getMessage();
        echo "<br><br><b>Stack Trace:</b><br>" . $e->getTraceAsString();
    }
});

// if (!User::where('role', 'admin')->exists()) {
//     User::create([...]);
// }
