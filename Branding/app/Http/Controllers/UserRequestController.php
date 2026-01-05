<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BrandingRequest;   // Model untuk Reg 1
use App\Models\BrandingRequest2;  // Model untuk Reg 2
use App\Models\ActivityLog;       // <--- WAJIB: Import Model ActivityLog
use App\Models\Notification;      // Import Model Notifikasi
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage; 

class UserRequestController extends Controller
{
    // =========================================================
    // 1. HALAMAN HOME (LANDING PAGE)
    // =========================================================
    public function index()
    {
        return view('user.home');
    }

    // =========================================================
    // 2. HALAMAN INPUT FORM (TOMBOL "INPUT DATA")
    // =========================================================
    public function create()
    {
        return view('user.input_request');
    }

    // =========================================================
    // 3. PROSES SIMPAN DATA (AUTO GENERATE ID + UPLOAD FOTO)
    // =========================================================
    public function store(Request $request)
    {
        // A. VALIDASI DATA
        $request->validate([
            'nama_toko'        => 'required',
            'lokasi'           => 'required',
            'area_sales'       => 'required',
            'brand'            => 'required',
            'jenis_permintaan' => 'required',
            'jenis_tools'      => 'required',
            'qty'              => 'required|numeric',
            'nama_sales'       => 'required',
            
            // Validasi Foto
            'foto_area'        => 'required|array|max:5',
            'foto_area.*'      => 'image|mimes:jpeg,png,jpg|max:2048',
            'foto_sugest'      => 'nullable|array|max:5',
            'foto_sugest.*'    => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // B. PROSES UPLOAD FOTO (LOOPING)
        
        // 1. Foto Area
        $fotoAreaPaths = [];
        if ($request->hasFile('foto_area')) {
            foreach ($request->file('foto_area') as $file) {
                $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('uploads/branding', $filename, 'public');
                $fotoAreaPaths[] = $path;
            }
        }

        // 2. Foto Sugest
        $fotoSugestPaths = [];
        if ($request->hasFile('foto_sugest')) {
            foreach ($request->file('foto_sugest') as $file) {
                $filename = time() . '_sugest_' . uniqid() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('uploads/branding', $filename, 'public');
                $fotoSugestPaths[] = $path;
            }
        }

        // C. LOGIKA REGIONAL & ID GENERATOR
        $userRegional = Auth::user()->regional;
        $newId = '';
        $model = null;

        // --- SKENARIO REGIONAL 1 (BS...) ---
        if ($userRegional == 'reg1' || $userRegional == 'Regional 1') { 
            $lastItem = BrandingRequest::where('request_id', 'LIKE', 'BS%')
                        ->orderByRaw('CAST(SUBSTRING(request_id, 3) AS UNSIGNED) DESC')
                        ->first();
            $lastNumber = $lastItem ? (int)substr($lastItem->request_id, 2) : 0;
            $newId = 'BS' . ($lastNumber + 1);
            $model = new BrandingRequest();
        } 
        // --- SKENARIO REGIONAL 2 (RB...) ---
        else {
            $lastItem = BrandingRequest2::where('request_id', 'LIKE', 'RB%')
                        ->orderByRaw('CAST(SUBSTRING(request_id, 3) AS UNSIGNED) DESC')
                        ->first();
            $lastNumber = $lastItem ? (int)substr($lastItem->request_id, 2) : 0;
            $newId = 'RB' . ($lastNumber + 1);
            $model = new BrandingRequest2();
        }

        // D. SIMPAN KE DATABASE (Mapping Sesuai Kolom Anda)
        
        // 1. Identitas & Waktu
        $model->submission_date       = Carbon::now(); 
        $model->request_id            = $newId;
        $model->email_address         = Auth::user()->email; 
        
        // 2. Sales Info
        $model->area_sales            = $request->area_sales;
        $model->nama_sales            = $request->nama_sales; 
        $model->nama_spv              = $request->nama_spv ?? '-';
        
        // 3. Toko Info
        $model->nama_toko             = strtoupper($request->nama_toko);
        $model->lokasi                = $request->lokasi; 
        
        // 4. Detail Permintaan
        $model->jenis_permintaan      = $request->jenis_permintaan; 
        $model->jenis_tools_branding  = $request->jenis_tools;
        $model->ukuran_tools_branding = $request->ukuran ?? '-';
        $model->qty_tools             = $request->qty; 
        $model->brand                 = $request->brand;
        
        // 5. Pengiriman & Ket
        $model->pengiriman            = $request->pengiriman ?? '-';
        $model->keterangan_tambahan   = $request->keterangan;

        // 6. Foto (JSON)
        $model->photo_area_pemasangan = json_encode($fotoAreaPaths);
        $model->photo_sugest_design   = json_encode($fotoSugestPaths);

        $model->save();

        // --- TAMBAHAN: CATAT LOG AKTIVITAS (INPUT BARU) ---
        ActivityLog::create([
            'user_id'               => Auth::id(),
            'action_type'           => 'INPUT BARU',
            'request_id'            => $newId,
            'nama_toko'             => strtoupper($request->nama_toko),
            'jenis_tools_branding'  => $request->jenis_tools,
            'ukuran_tools_branding' => $request->ukuran ?? '-',
            'qty_tools'             => $request->qty,
            'keterangan_tambahan'   => $request->keterangan
        ]);
        // --------------------------------------------------

        $tabTujuan = '';
        if (str_starts_with($newId, 'BS')) {
            $tabTujuan = 'regional1'; 
        } else {
            $tabTujuan = 'regional2'; 
        }

        // Buat Notifikasi
        Notification::create([
            'type'    => 'INPUT',
            'title'   => 'Request Branding Baru',
            'message' => "Sales {$request->nama_sales} (Area: {$request->area_sales}) mengajukan request ID: $newId.",
            'url'     => route('admin.request.branding', ['tab' => $tabTujuan, 'search' => $newId]),
            'is_read' => false,
        ]);

        return back()
            ->with('success', 'Data branding berhasil disimpan.')
            ->with('created_id', $newId);
    }

    // =========================================================
    // 4. HALAMAN REVISI (TOMBOL "REVISI DATA")
    // =========================================================
    public function revisi()
    {
        return view('user.revisi_request'); 
    }

    // =========================================================
    // 5. PROSES TRACKING (DENGAN JOIN STATUS)
    // =========================================================
    public function track(Request $request)
    {
        $keyword = $request->input('keyword');

        // Jika tidak ada keyword, kembalikan hasil kosong
        if(!$keyword) {
            return view('user.tracking_result', ['results' => collect([]), 'keyword' => '']);
        }

        // 1. AMBIL EMAIL USER YANG SEDANG LOGIN (KUNCI KEAMANAN)
        $userEmail = Auth::user()->email;

        // --- QUERY REGIONAL 1 (BS...) ---
        $data1 = BrandingRequest::query()
            ->select('branding_requests.*', 'branding_statuses.*') 
            ->leftJoin('branding_statuses', 'branding_requests.request_id', '=', 'branding_statuses.request_id')
            
            // [PENTING] FILTER HANYA DATA MILIK USER INI
            ->where('branding_requests.email_address', $userEmail)
            
            // BARU FILTER BERDASARKAN PENCARIAN (ID ATAU NAMA TOKO)
            ->where(function($q) use ($keyword) {
                $q->where('branding_requests.request_id', 'LIKE', "%$keyword%")
                  ->orWhere('branding_requests.nama_toko', 'LIKE', "%$keyword%");
            })
            ->get();

        // --- QUERY REGIONAL 2 (RB...) ---
        $table2 = (new BrandingRequest2)->getTable(); 
        
        $data2 = BrandingRequest2::query()
            ->select($table2.'.*', 'branding_statuses.*')
            ->leftJoin('branding_statuses', $table2.'.request_id', '=', 'branding_statuses.request_id')
            
            // [PENTING] FILTER HANYA DATA MILIK USER INI
            ->where($table2.'.email_address', $userEmail)
            
            // BARU FILTER BERDASARKAN PENCARIAN
            ->where(function($q) use ($keyword, $table2) {
                $q->where($table2.'.request_id', 'LIKE', "%$keyword%")
                  ->orWhere($table2.'.nama_toko', 'LIKE', "%$keyword%");
            })
            ->get();
        
        // GABUNGKAN HASIL KEDUANYA
        $results = $data1->merge($data2);

        return view('user.tracking_result', compact('results', 'keyword'));
    }

    // =========================================================
    // 6. CEK ID UNTUK REVISI (Mencari Data)
    // =========================================================
    public function checkRevisi(Request $request)
    {
        $request->validate([
            'request_id' => 'required'
        ]);

        $id = $request->request_id;
        $data = null;
        $tableType = '';

        if (str_starts_with($id, 'BS')) {
            $data = BrandingRequest::where('request_id', $id)->first();
            $tableType = 'reg1';
        } elseif (str_starts_with($id, 'RB')) {
            $data = BrandingRequest2::where('request_id', $id)->first();
            $tableType = 'reg2';
        }

        if (!$data) {
            return back()->withErrors(['request_id' => 'ID Request tidak ditemukan!']);
        }

        if ($data->email_address !== Auth::user()->email) {
            return back()->withErrors(['request_id' => 'Anda tidak memiliki akses untuk mengedit data ini!']);
        }

        return view('user.form_edit_request', compact('data', 'tableType'));
    }

    // =========================================================
    // 7. PROSES UPDATE DATA REVISI
    // =========================================================
   // =========================================================
    // 7. PROSES UPDATE DATA REVISI (SIMPAN SEBAGAI DATA BARU)
    // =========================================================
    public function updateRevisi(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'request_id'       => 'required',
            'table_type'       => 'required', // reg1 atau reg2
            'nama_toko'        => 'required',
            'lokasi'           => 'required',
            'qty'              => 'required|numeric',
            
            'foto_area'        => 'nullable|array|max:5',
            'foto_area.*'      => 'image|mimes:jpeg,png,jpg|max:2048',
            'foto_sugest'      => 'nullable|array|max:5',
            'foto_sugest.*'    => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Cari Data Asli (SOURCE)
        $originalData = null;
        $newModel = null;

        if ($request->table_type == 'reg1') {
            $originalData = BrandingRequest::where('request_id', $request->request_id)->first();
            $newModel = new BrandingRequest(); // Siapkan wadah baru Reg 1
        } else {
            $originalData = BrandingRequest2::where('request_id', $request->request_id)->first();
            $newModel = new BrandingRequest2(); // Siapkan wadah baru Reg 2
        }

        if (!$originalData) {
            return redirect()->route('user.request.revisi')->withErrors(['Data asli tidak ditemukan.']);
        }

        // 3. GENERATE ID BARU KHUSUS REVISI
        // Format: ID_ASLI + "-REV-" + Waktu (Biar unik dan tidak bentrok)
        // Contoh: BS105 -> BS105-REV-1030 (Revisi jam 10:30)
        $newId = $originalData->request_id . '-REV-' . date('dmy'); 

        // 4. COPY DATA & UPDATE DENGAN INPUTAN BARU
        // Kita isi $newModel dengan data inputan form
        $newModel->submission_date       = Carbon::now(); // Tanggal submit baru
        $newModel->request_id            = $newId;        // ID BARU
        $newModel->email_address         = Auth::user()->email;
        
        $newModel->nama_toko             = strtoupper($request->nama_toko);
        $newModel->lokasi                = $request->lokasi;
        $newModel->area_sales            = $request->area_sales;
        $newModel->nama_sales            = $request->nama_sales;
        $newModel->nama_spv              = $request->nama_spv ?? '-';
        $newModel->brand                 = $request->brand;
        $newModel->jenis_permintaan      = $request->jenis_permintaan;
        $newModel->jenis_tools_branding  = $request->jenis_tools;
        $newModel->ukuran_tools_branding = $request->ukuran ?? '-';
        $newModel->qty_tools             = $request->qty;
        $newModel->pengiriman            = $request->pengiriman ?? '-';
        $newModel->keterangan_tambahan   = $request->keterangan . ' (DATA REVISI)'; // Kasih tanda di keterangan

        // 5. ATUR FOTO (Pakai foto baru jika ada upload, pakai foto lama jika tidak ada)
        
        // Foto Area
        if ($request->hasFile('foto_area')) {
            $fotoAreaPaths = [];
            foreach ($request->file('foto_area') as $file) {
                $filename = time() . '_revisi_' . uniqid() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('uploads/branding', $filename, 'public');
                $fotoAreaPaths[] = $path;
            }
            $newModel->photo_area_pemasangan = json_encode($fotoAreaPaths);
        } else {
            // Jika user tidak upload foto baru, pakai foto lama
            $newModel->photo_area_pemasangan = $originalData->photo_area_pemasangan;
        }

        // Foto Sugest
        if ($request->hasFile('foto_sugest')) {
            $fotoSugestPaths = [];
            foreach ($request->file('foto_sugest') as $file) {
                $filename = time() . '_revisi_sugest_' . uniqid() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('uploads/branding', $filename, 'public');
                $fotoSugestPaths[] = $path;
            }
            $newModel->photo_sugest_design = json_encode($fotoSugestPaths);
        } else {
            // Pakai foto lama
            $newModel->photo_sugest_design = $originalData->photo_sugest_design;
        }

        // 6. SIMPAN SEBAGAI DATA BARU
        $newModel->save();

        // ----------------------------------------------
        // 7. PENCATATAN LOG & STATUS
        // ----------------------------------------------

        // A. Log Aktivitas
        ActivityLog::create([
            'user_id'               => Auth::id(),
            'action_type'           => 'INPUT REVISI', // Tipe aksi beda
            'request_id'            => $newId,         // Catat ID Baru
            'nama_toko'             => strtoupper($request->nama_toko),
            'jenis_tools_branding'  => $request->jenis_tools,
            'ukuran_tools_branding' => $request->ukuran ?? '-',
            'qty_tools'             => $request->qty,
            'keterangan_tambahan'   => 'Revisi dari ' . $originalData->request_id
        ]);

        // B. Tentukan Tab Admin
        $tabTujuan = str_starts_with($originalData->request_id, 'BS') ? 'regional1' : 'regional2';

        // C. Buat Notifikasi Admin
        Notification::create([
            'type'    => 'REVISI_BARU',
            'title'   => 'Data Revisi Masuk',
            'message' => "Sales {$request->nama_sales} membuat revisi baru. ID Asli: {$originalData->request_id} -> ID Baru: {$newId}",
            'url'     => route('admin.request.branding', ['tab' => $tabTujuan, 'search' => $newId]),
            'is_read' => false,
        ]);

        // D. (OPSIONAL) Jika Anda punya tabel BrandingStatus, buat status awal untuk revisi ini
        // BrandingStatus::create(['request_id' => $newId, 'status' => 'MENUNGGU APPROVAL', ...]);

        return redirect()->route('user.home')
            ->with('success', "Revisi Berhasil! Data baru telah dibuat dengan ID: $newId");
    }

    // =========================================================
    // 8. HALAMAN LOG AKTIVITAS (VIEW TABEL LOG)
    // =========================================================
    public function historyLog()
    {
        // Ambil log milik user yg login, urutkan terbaru
        $logs = ActivityLog::where('user_id', Auth::id())
                            ->latest()
                            ->get();

        return view('user.log_aktivitas', compact('logs'));
    }


//     public function uploadPhoto(Request $request)
// {
//     $request->validate([
//         'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
//     ]);

//     $user = Auth::user();

//     if ($request->hasFile('profile_photo')) {
//         // Hapus foto lama jika ada (opsional)
//         // if ($user->profile_photo) { Storage::disk('public')->delete($user->profile_photo); }

//         $path = $request->file('profile_photo')->store('profile-photos', 'public');
//         $user->profile_photo = $path;
//         $user->save();
//     }

//     return back()->with('success', 'Foto profil berhasil diperbarui!');
// }
}