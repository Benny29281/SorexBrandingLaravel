<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BrandingRequest;   // Model untuk Reg 1
use App\Models\BrandingRequest2;  // Model untuk Reg 2
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
    $model->jenis_permintaan      = $request->jenis_permintaan; // Ex: BARU / PEREMAJAAN
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

    $tabTujuan = '';

    // Cek 2 huruf pertama dari ID yang baru dibuat
    if (str_starts_with($newId, 'BS')) {
        $tabTujuan = 'regional1'; // Link ke Tab Merah (JT, DK, LP)
    } else {
        $tabTujuan = 'regional2'; // Link ke Tab Hijau (JB, JR)
    }

    // Buat Notifikasi
    \App\Models\Notification::create([
        'type'    => 'INPUT',
        'title'   => 'Request Branding Baru',
        'message' => "Toko {$request->nama_toko} (Area: {$request->area_sales}) mengajukan request ID: $newId.",
        
        // Link otomatis membawa parameter tab & search ID
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

        // Jika keyword kosong, kembalikan kosong
        if(!$keyword) {
            return view('user.tracking_result', ['results' => collect([]), 'keyword' => '']);
        }

        // --- QUERY REGIONAL 1 (Gabung Tabel Request + Status) ---
        // Kita ambil semua data Request (*) DAN semua data Status (*)
        $data1 = BrandingRequest::query()
            ->select('branding_requests.*', 'branding_statuses.*') 
            ->leftJoin('branding_statuses', 'branding_requests.request_id', '=', 'branding_statuses.request_id')
            ->where(function($q) use ($keyword) {
                $q->where('branding_requests.request_id', 'LIKE', "%$keyword%")
                  ->orWhere('branding_requests.nama_toko', 'LIKE', "%$keyword%");
            })
            ->get();

        // --- QUERY REGIONAL 2 (Gabung Tabel Request2 + Status) ---
        // Kita ambil nama tabelnya otomatis biar aman
        $table2 = (new BrandingRequest2)->getTable(); 
        
        $data2 = BrandingRequest2::query()
            ->select($table2.'.*', 'branding_statuses.*')
            ->leftJoin('branding_statuses', $table2.'.request_id', '=', 'branding_statuses.request_id')
            ->where(function($q) use ($keyword, $table2) {
                $q->where($table2.'.request_id', 'LIKE', "%$keyword%")
                  ->orWhere($table2.'.nama_toko', 'LIKE', "%$keyword%");
            })
            ->get();
        
        // Gabungkan Hasil Pencarian
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

        // Cek apakah ID diawali BS (Regional 1) atau RB (Regional 2)
        if (str_starts_with($id, 'BS')) {
            $data = BrandingRequest::where('request_id', $id)->first();
            $tableType = 'reg1';
        } elseif (str_starts_with($id, 'RB')) {
            $data = BrandingRequest2::where('request_id', $id)->first();
            $tableType = 'reg2';
        }

        // Validasi 1: Data tidak ditemukan
        if (!$data) {
            return back()->withErrors(['request_id' => 'ID Request tidak ditemukan!']);
        }

        // Validasi 2: Keamanan (Cek apakah email pemilik data sama dengan user login)
        if ($data->email_address !== Auth::user()->email) {
            return back()->withErrors(['request_id' => 'Anda tidak memiliki akses untuk mengedit data ini!']);
        }

        // Jika lolos, tampilkan view form edit dengan membawa datanya
        return view('user.form_edit_request', compact('data', 'tableType'));
    }

    // =========================================================
    // 7. PROSES UPDATE DATA REVISI
    // =========================================================
    public function updateRevisi(Request $request)
    {
        // 1. Validasi Input (Foto dibuat nullable/tidak wajib diisi jika tidak mau diganti)
        $request->validate([
            'request_id'       => 'required',
            'table_type'       => 'required', // reg1 atau reg2
            'nama_toko'        => 'required',
            'lokasi'           => 'required',
            'qty'              => 'required|numeric',
            
            // Foto tidak wajib (nullable), tapi jika diisi harus gambar
            'foto_area'        => 'nullable|array|max:5',
            'foto_area.*'      => 'image|mimes:jpeg,png,jpg|max:2048',
            'foto_sugest'      => 'nullable|array|max:5',
            'foto_sugest.*'    => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Tentukan Model Berdasarkan Hidden Input 'table_type'
        $model = null;
        if ($request->table_type == 'reg1') {
            $model = BrandingRequest::where('request_id', $request->request_id)->first();
        } else {
            $model = BrandingRequest2::where('request_id', $request->request_id)->first();
        }

        if (!$model) {
            return redirect()->route('user.request.revisi')->withErrors(['Data tidak ditemukan saat update.']);
        }

        // 3. Update Data Teks
        $model->nama_toko             = strtoupper($request->nama_toko);
        $model->lokasi                = $request->lokasi;
        $model->area_sales            = $request->area_sales;
        $model->nama_sales            = $request->nama_sales;
        $model->nama_spv              = $request->nama_spv ?? '-';
        $model->brand                 = $request->brand;
        $model->jenis_permintaan      = $request->jenis_permintaan;
        $model->jenis_tools_branding  = $request->jenis_tools;
        $model->ukuran_tools_branding = $request->ukuran ?? '-';
        $model->qty_tools             = $request->qty;
        $model->pengiriman            = $request->pengiriman ?? '-';
        $model->keterangan_tambahan   = $request->keterangan;

        // 4. Update Foto (Hanya jika user upload foto baru)
        
        // Cek Foto Area Baru
        if ($request->hasFile('foto_area')) {
            $fotoAreaPaths = [];
            foreach ($request->file('foto_area') as $file) {
                $filename = time() . '_revisi_' . uniqid() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('uploads/branding', $filename, 'public');
                $fotoAreaPaths[] = $path;
            }
            // Timpa data lama dengan data baru
            $model->photo_area_pemasangan = json_encode($fotoAreaPaths);
        }

        // Cek Foto Sugest Baru
        if ($request->hasFile('foto_sugest')) {
            $fotoSugestPaths = [];
            foreach ($request->file('foto_sugest') as $file) {
                $filename = time() . '_revisi_sugest_' . uniqid() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('uploads/branding', $filename, 'public');
                $fotoSugestPaths[] = $path;
            }
            // Timpa data lama
            $model->photo_sugest_design = json_encode($fotoSugestPaths);
        }

        $model->save();

        $tabTujuan = '';
    $idRequest = $request->request_id; // Ambil ID dari Form

    // Cek 2 huruf pertama
    if (str_starts_with($idRequest, 'BS')) {
        $tabTujuan = 'regional1';
    } else {
        $tabTujuan = 'regional2';
    }

    // Buat Notifikasi Revisi
    \App\Models\Notification::create([
        'type'    => 'REVISI',
        'title'   => 'Revisi Data Masuk',
        'message' => "Sales {$request->nama_sales} telah merevisi data untuk ID: {$idRequest}.",
        
        // Link otomatis membawa parameter tab & search ID
        'url'     => route('admin.request.branding', ['tab' => $tabTujuan, 'search' => $idRequest]),
        
        'is_read' => false,
    ]);

// return redirect()...

        return redirect()->route('user.home')->with('success', "Data ID {$request->request_id} berhasil direvisi!");
    }

    
}