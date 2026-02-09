<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Maatwebsite\Excel\Facades\Excel;

// Models
use App\Models\BrandingRequest;   // Regional 1
use App\Models\BrandingRequest2;  // Regional 2
use App\Models\BrandingStatus;    // Status Tracking

// Imports
use App\Imports\BrandingRequestImport; 
use App\Imports\BrandingStatusImport;

class BrandingRequestController extends Controller
{
    // ==========================================================
    // 1. IMPORT DATA EXCEL (REQUEST BARU)
    // ==========================================================
    public function import(Request $request) 
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv,txt'
        ]);

        DB::beginTransaction();

        try {
            Excel::import(new BrandingRequestImport, $request->file('file'));
            
            DB::commit();
            return back()->with('success', 'Data Berhasil Diupload! Data yang bergeser sudah diperbaiki otomatis.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal Upload: ' . $e->getMessage());
        }
    }

    // ==========================================================
    // 2. TAMPILAN DATA REGIONAL 1 SAJA (TABLE 1)
    // ==========================================================
    public function indexData1()
    {
        $data = BrandingRequest::latest()->paginate(20);
        return view('admin.data.table1', compact('data'));
    }

    // ==========================================================
    // 3. TAMPILAN UTAMA REQUEST BRANDING (HALAMAN REQUEST)
    //    *Revisi: Sudah ditambahkan fitur SEARCH*
    // ==========================================================
    public function indexRequest(Request $request)
    {
        $search = trim($request->input('search'));
        
        // --- QUERY REGIONAL 1 ---
        $query1 = BrandingRequest::query();
        if ($search) {
            $query1->where(function($q) use ($search) {
                $q->where('request_id', 'LIKE', "%{$search}%")
                  ->orWhere('nama_toko', 'LIKE', "%{$search}%")
                  ->orWhere('nama_sales', 'LIKE', "%{$search}%")
                  ->orWhere('area_sales', 'LIKE', "%{$search}%");
            });
        }
        // Sortir berdasarkan angka di belakang ID (misal: BS10, BS2 -> BS10 dulu)
        $data1 = $query1->orderByRaw('CAST(SUBSTRING(request_id, 3) AS UNSIGNED) DESC')
                        ->paginate(50, ['*'], 'page_reg1')
                        ->appends($request->all());

        // --- QUERY REGIONAL 2 ---
        $query2 = BrandingRequest2::query();
        if ($search) {
            $query2->where(function($q) use ($search) {
                $q->where('request_id', 'LIKE', "%{$search}%")
                  ->orWhere('nama_toko', 'LIKE', "%{$search}%")
                  ->orWhere('nama_sales', 'LIKE', "%{$search}%")
                  ->orWhere('area_sales', 'LIKE', "%{$search}%");
            });
        }
        $data2 = $query2->orderByRaw('CAST(SUBSTRING(request_id, 3) AS UNSIGNED) DESC')
                        ->paginate(50, ['*'], 'page_reg2')
                        ->appends($request->all());

        return view('admin.branding.index', compact('data1', 'data2'));
    }

    public function importStatus(Request $request) 
    {
        $request->validate([
            'file_status' => 'required|mimes:xlsx,xls,csv'
        ]);

        DB::beginTransaction();
        try {
            Excel::import(new BrandingStatusImport, $request->file('file_status'));
            
            DB::commit();
            return back()->with('success', 'Data History Status Berhasil Diimport!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal Import Status: ' . $e->getMessage());
        }
    }

    // ==========================================================
    // 4. HAPUS DATA REQUEST
    // ==========================================================
    public function destroy($region, $id)
    {
        if ($region == 'reg1') {
            $item = BrandingRequest::findOrFail($id);
            $item->delete();
        } elseif ($region == 'reg2') {
            $item = BrandingRequest2::findOrFail($id);
            $item->delete();
        }

        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }

    // ==========================================================
    // 5. EDIT DATA REQUEST
    // ==========================================================
    public function edit($region, $id)
    {
        return "Halaman Edit untuk Regional: $region, ID: $id"; 
    }

  public function storeStatus(Request $request)
{
    $action = $request->input('action');
    $reqId  = $request->input('request_id');
    $statusId = $request->input('id'); 

    if (!$reqId) {
        return back()->with('error', 'Gagal: Request ID tidak ditemukan.');
    }

    // --- 1. LOGIKA HAPUS ---
    if ($action == 'hapus') {
        if ($statusId) {
            \App\Models\BrandingStatus::where('id', $statusId)->delete();
        } else {
            \App\Models\BrandingStatus::where('request_id', $reqId)->delete();
        }
        return back()->with('success', 'Data status berhasil DIHAPUS!');
    }

    // --- 2. UPDATE UKURAN & SUBMISSION DATE DI TABEL ASLI ---
    if ($reqId) {
        $prefix  = strtoupper(substr($reqId, 0, 2));
        $now = now(); // Ambil waktu server saat ini (jam:menit:detik)
        
        // Pilih Model berdasarkan Prefix Regional
        $modelRequest = in_array($prefix, ['BS', 'JT', 'DK', 'LP']) 
                        ? \App\Models\BrandingRequest::class 
                        : \App\Models\BrandingRequest2::class;

        // Cari data asli di tabel BrandingRequest/2
        $existingReq = $modelRequest::where('request_id', $reqId)->first();

        if ($existingReq) {
            $updateParent = [];

            // Update Ukuran jika ada input
            if ($request->has('ukuran_fix') && $request->filled('ukuran_fix')) {
                $updateParent['ukuran_tools_branding'] = $request->input('ukuran_fix');
            }

            // REVISI: Logika Jam Otomatis untuk Submission Date
            if ($request->filled('submission_date')) {
                $newSubDate = $request->input('submission_date'); // Format: YYYY-MM-DD
                $oldSubDate = \Carbon\Carbon::parse($existingReq->submission_date);

                // Jika tanggal diinput SAMA dengan tanggal lama di DB, pertahankan jam lama
                if ($oldSubDate->format('Y-m-d') == $newSubDate) {
                    $updateParent['submission_date'] = $oldSubDate;
                } else {
                    // Jika tanggal BERUBAH, tempelkan jam SEKARANG
                    $updateParent['submission_date'] = \Carbon\Carbon::parse($newSubDate)
                        ->setTime($now->hour, $now->minute, $now->second);
                }
            }

            // Jalankan update ke tabel BrandingRequest atau BrandingRequest2
            if (!empty($updateParent)) {
                $existingReq->update($updateParent);
            }
        }
    }

    // --- 3. TENTUKAN STATUS ---
    $statusPekerjaan = 'PROSES';
    $msg = 'Data berhasil disimpan!';

    if ($action == 'selesai') {
        $statusPekerjaan = 'SELESAI';
    } elseif ($action == 'update') {
        if ($statusId) {
            $existing = \App\Models\BrandingStatus::find($statusId);
            if ($existing && $existing->status_pekerjaan == 'SELESAI') {
                $statusPekerjaan = 'SELESAI';
            }
        }
    }

    // --- 4. SIAPKAN DATA STATUS DENGAN LOGIKA FIX TIME ---
    $now = now();
    
    // Ambil data lama dari database untuk pengecekan jam
    $existingStatus = $statusId ? \App\Models\BrandingStatus::find($statusId) : null;

    // Fungsi pembantu agar jam tidak berubah jika tanggalnya sama
    $getFixDateTime = function($fieldName, $newDate) use ($existingStatus, $now) {
        if (!$newDate) return null;

        // Jika data lama ada untuk kolom ini
        if ($existingStatus && $existingStatus->$fieldName) {
            $oldFullDateTime = \Carbon\Carbon::parse($existingStatus->$fieldName);
            
            // Jika tanggal yang dikirim sama dengan tanggal di DB, gunakan jam yang lama
            if ($oldFullDateTime->format('Y-m-d') == $newDate) {
                return $oldFullDateTime; 
            }
        }

        // Jika tanggal berubah atau data baru, gabungkan tanggal input dengan jam sekarang
        return \Carbon\Carbon::parse($newDate)->setTime($now->hour, $now->minute, $now->second);
    };

    $dataToSave = [
        'request_id'            => $reqId, 
        'via'                   => 'WEB',
        'ukuran_fix'            => $request->input('ukuran_fix'),
        'pembuatan_design'      => $getFixDateTime('pembuatan_design', $request->pembuatan_design),
        'approve_leader'        => $getFixDateTime('approve_leader', $request->approve_leader),
        'approve_toko'          => $getFixDateTime('approve_toko', $request->approve_toko),
        'konfirmasi_design'     => $getFixDateTime('konfirmasi_design', $request->konfirmasi_design),
        'tanggal_masuk_vendor'  => $getFixDateTime('tanggal_masuk_vendor', $request->tanggal_masuk_vendor),
        'nama_vendor'           => $request->nama_vendor,
        'sj_di_terima_tasya'    => $getFixDateTime('sj_di_terima_tasya', $request->sj_di_terima_tasya),
        'po_selesai_gudang_fr'  => $getFixDateTime('po_selesai_gudang_fr', $request->po_selesai_gudang_fr),
        'packing_barang_fr'     => $request->packing_barang_fr,
        'kirim_ke_dadap'        => $getFixDateTime('kirim_ke_dadap', $request->gudang_fr_kirim_ke_dadap), 
        'terima_di_dadap'       => $getFixDateTime('terima_di_dadap', $request->barang_diterima_dadap),    
        'kirim_ke_ekspedisi'    => $getFixDateTime('kirim_ke_ekspedisi', $request->kirim_ke_ekspedisi),
        'nomor_resi'            => $request->nomor_resi,
        'konfirmasi_penerimaan' => $getFixDateTime('konfirmasi_penerimaan', $request->konfirmasi_penerimaan_barang), 
        'status_pekerjaan'      => $statusPekerjaan,
    ];

    // --- 5. EKSEKUSI ---
    try {
        if ($statusId) {
            \App\Models\BrandingStatus::where('id', $statusId)->update($dataToSave);
            $msg = "Status berhasil di update";
        } else {
            \App\Models\BrandingStatus::create($dataToSave);
            $msg = "Status baru berhasil ditambahkan!";
        }
        return back()->with('success', $msg);
    } catch (\Exception $e) {
        return back()->with('error', 'Terjadi Kesalahan: ' . $e->getMessage());
    }
}

    // ==========================================================
    // 7. FUNGSI HALAMAN STATUS BRANDING (REVISI ROBUST)
    // ==========================================================
    public function indexStatus(Request $request)
    {
        $search = trim($request->input('search'));
        
        // --- DATA REGIONAL 1 (BS) ---
        $scopeReg1 = BrandingRequest::pluck('request_id')->toArray();

        // Query Proses Reg 1
        $q1_proses = BrandingStatus::whereIn('request_id', $scopeReg1)->where('status_pekerjaan', 'PROSES');
        if($search) {
            $q1_proses->where(function($q) use ($search) {
                $q->where('request_id', 'LIKE', "%{$search}%")
                  ->orWhere('nama_vendor', 'LIKE', "%{$search}%")
                  ->orWhere('nomor_resi', 'LIKE', "%{$search}%")
                  ->orWhereIn('request_id', BrandingRequest::where('nama_toko', 'LIKE', "%{$search}%")->pluck('request_id'));
            });
        }
        $data1_proses = $q1_proses->latest()->paginate(50, ['*'], 'p1_proc')->appends($request->all());

        // Query Selesai Reg 1
        $q1_selesai = BrandingStatus::whereIn('request_id', $scopeReg1)->where('status_pekerjaan', 'SELESAI');
        if($search) {
            $q1_selesai->where(function($q) use ($search) {
                $q->where('request_id', 'LIKE', "%{$search}%")
                  ->orWhere('nama_vendor', 'LIKE', "%{$search}%")
                  ->orWhere('nomor_resi', 'LIKE', "%{$search}%")
                  ->orWhereIn('request_id', BrandingRequest::where('nama_toko', 'LIKE', "%{$search}%")->pluck('request_id'));
            });
        }
        $data1_selesai = $q1_selesai->latest()->paginate(50, ['*'], 'p1_done')->appends($request->all());


        // --- DATA REGIONAL 2 (RB) ---
        $scopeReg2 = BrandingRequest2::pluck('request_id')->toArray();

        // Query Proses Reg 2
        $q2_proses = BrandingStatus::whereIn('request_id', $scopeReg2)->where('status_pekerjaan', 'PROSES');
        if($search) {
            $q2_proses->where(function($q) use ($search) {
                $q->where('request_id', 'LIKE', "%{$search}%")
                  ->orWhere('nama_vendor', 'LIKE', "%{$search}%")
                  ->orWhere('nomor_resi', 'LIKE', "%{$search}%")
                  ->orWhereIn('request_id', BrandingRequest2::where('nama_toko', 'LIKE', "%{$search}%")->pluck('request_id'));
            });
        }
        $data2_proses = $q2_proses->latest()->paginate(50, ['*'], 'p2_proc')->appends($request->all());

        // Query Selesai Reg 2
        $q2_selesai = BrandingStatus::whereIn('request_id', $scopeReg2)->where('status_pekerjaan', 'SELESAI');
        if($search) {
            $q2_selesai->where(function($q) use ($search) {
                $q->where('request_id', 'LIKE', "%{$search}%")
                  ->orWhere('nama_vendor', 'LIKE', "%{$search}%")
                  ->orWhere('nomor_resi', 'LIKE', "%{$search}%")
                  ->orWhereIn('request_id', BrandingRequest2::where('nama_toko', 'LIKE', "%{$search}%")->pluck('request_id'));
            });
        }
        $data2_selesai = $q2_selesai->latest()->paginate(50, ['*'], 'p2_done')->appends($request->all());

        // Tempel Data Parent (Wajib)
        foreach ($data1_proses as $i) $i->setRelation('parent_data', BrandingRequest::where('request_id', $i->request_id)->first());
        foreach ($data1_selesai as $i) $i->setRelation('parent_data', BrandingRequest::where('request_id', $i->request_id)->first());
        foreach ($data2_proses as $i) $i->setRelation('parent_data', BrandingRequest2::where('request_id', $i->request_id)->first());
        foreach ($data2_selesai as $i) $i->setRelation('parent_data', BrandingRequest2::where('request_id', $i->request_id)->first());

        return view('admin.status.index', compact(
            'data1_proses', 'data1_selesai',
            'data2_proses', 'data2_selesai'
        ));
    }
}