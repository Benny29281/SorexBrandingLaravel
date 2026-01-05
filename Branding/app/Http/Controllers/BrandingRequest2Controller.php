<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

// Panggil Model
use App\Models\BrandingRequest;
use App\Models\BrandingRequest2;
use App\Models\BrandingStatus;

// Panggil Import
use App\Imports\BrandingRequest2Import;
use App\Imports\BrandingStatusImport;

class BrandingRequest2Controller extends Controller
{
    // ==========================================================
    // 1. IMPORT DATA BRANDING REQUEST (EXCEL)
    // ==========================================================
    public function import2(Request $request) 
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        DB::beginTransaction();
        try {
            // Pastikan file Import Anda (BrandingRequest2Import) sudah menggunakan updateOrCreate
            Excel::import(new BrandingRequest2Import, $request->file('file'));
            
            DB::commit();
            return back()->with('success', 'Data Berhasil Diupload! Data ganda diperbarui, data baru ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal Upload: ' . $e->getMessage());
        }
    }

    // ==========================================================
    // 2. IMPORT DATA STATUS / TRACKING (EXCEL)
    // ==========================================================
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
    // 3. TAMPILAN DATA (TABLE)
    // ==========================================================
    
    // Tampilan Khusus Data Regional 1 (Jika diperlukan)
    public function indexData2()
    {
        $data = BrandingRequest2::latest()->paginate(20);
        return view('admin.data.table1', compact('data'));
    }

    // Tampilan Gabungan Request (Halaman Utama)
    public function indexRequest()
    {
        // Mengurutkan berdasarkan angka di belakang ID (Misal: REG-001, REG-002)
        // Pastikan ID minimal 3 karakter agar SUBSTRING tidak error
        $data1 = BrandingRequest::orderByRaw('CAST(SUBSTRING(request_id, 3) AS UNSIGNED) DESC')
                    ->paginate(50, ['*'], 'page_reg1');

        $data2 = BrandingRequest2::orderByRaw('CAST(SUBSTRING(request_id, 3) AS UNSIGNED) DESC')
                    ->paginate(50, ['*'], 'page_reg2');

        return view('admin.branding.index', compact('data1', 'data2'));
    }

    // ==========================================================
    // 4. CRUD (DELETE & EDIT)
    // ==========================================================
    public function destroy($region, $id)
    {
        try {
            if ($region == 'reg1') {
                $item = BrandingRequest::findOrFail($id);
                $item->delete();
            } elseif ($region == 'reg2') {
                $item = BrandingRequest2::findOrFail($id);
                $item->delete();
            }
            return redirect()->back()->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data tidak ditemukan atau gagal dihapus.');
        }
    }

    public function edit($region, $id)
    {
        // Anda bisa mengarahkan ke view edit jika sudah ada
        return "Fitur Edit untuk Regional: $region, ID: $id (Belum tersedia view-nya)"; 
    }

    // ==========================================================
    // 5. HALAMAN STATUS / MONITORING (DENGAN SEARCH)
    // ==========================================================
    public function indexStatus2(Request $request)
    {
        $search = trim($request->input('search'));
        
        // Logika Sorting ID
        $sortLogic = 'CAST(SUBSTRING(request_id, 3) AS UNSIGNED) DESC';

        // --- QUERY BUILDER (Fungsi Bantuan agar tidak koding ulang) ---
        // $prefix: Kode awal ID (Misal 'JT', 'LP', atau 'BS' sesuai database Anda)
        // $status: 'PROSES' atau 'SELESAI'
        $getData = function($prefix, $status) use ($search, $sortLogic) {
            $query = BrandingStatus::query();

            // Filter berdasarkan Prefix ID (Sesuaikan dengan data asli Anda!)
            // Jika ID di database Anda campur (contoh: JT-01, LP-02), pakai LIKE biasa
            // Disini saya pakai logika kode lama Anda (BS% dan RB%)
            $query->where('request_id', 'LIKE', $prefix . '%');
            
            // Filter Status
            if ($status == 'SELESAI') {
                $query->where('status_pekerjaan', 'SELESAI');
            } else {
                $query->where('status_pekerjaan', '!=', 'SELESAI');
            }

            // *** LOGIKA SEARCH (PENTING) ***
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('request_id', 'LIKE', "%{$search}%")
                      ->orWhere('nama_toko', 'LIKE', "%{$search}%")
                      ->orWhere('nama_vendor', 'LIKE', "%{$search}%");
                });
            }

            return $query->orderByRaw($sortLogic);
        };

        // Eksekusi Query
        // PENTING: Pastikan 'BS%' dan 'RB%' sesuai dengan Format ID di Excel Anda
        $data1_proses  = $getData('BS', 'PROSES')->paginate(15, ['*'], 'p1_proc');
        $data1_selesai = $getData('BS', 'SELESAI')->paginate(15, ['*'], 'p1_done');
        
        $data2_proses  = $getData('RB', 'PROSES')->paginate(15, ['*'], 'p2_proc');
        $data2_selesai = $getData('RB', 'SELESAI')->paginate(15, ['*'], 'p2_done');

        return view('admin.status.index', compact(
            'data1_proses', 'data1_selesai',
            'data2_proses', 'data2_selesai',
            'search' // Kirim balik keyword search ke view agar tidak hilang
        ));
    }

    // ==========================================================
    // 6. SIMPAN / UPDATE STATUS (LOGIKA UTAMA)
    // ==========================================================
    // ==========================================================
    // 6. SIMPAN / UPDATE STATUS (LOGIKA AMAN & TIDAK MERUSAK DATA ASLI)
    // ==========================================================
    public function storeStatus(Request $request)
    {
        // 1. Ambil Action (Simpan / Selesai / Revisi / Hapus)
        $action = $request->input('action');
        $reqId  = $request->input('request_id');

        // Validasi ID Wajib Ada
        if (!$reqId) {
            return back()->with('error', 'Gagal: Request ID tidak ditemukan.');
        }

        // --- A. JIKA TOMBOL HAPUS DITEKAN ---
        if ($action == 'hapus') {
            // Hapus data hanya di tabel STATUS (Data asli di Request aman)
            BrandingStatus::where('request_id', $reqId)->delete();
            return back()->with('success', 'Data status tracking berhasil DIHAPUS!');
        }

        // --- B. TENTUKAN STATUS PEKERJAAN ---
        $statusPekerjaan = 'PROSES'; // Default
        $msg = 'Data berhasil diperbarui!';

        if ($action == 'selesai') {
            $statusPekerjaan = 'SELESAI';
            $msg = 'Status diubah menjadi SELESAI.';
        } elseif ($action == 'revisi') {
            $statusPekerjaan = 'PROSES';
            $msg = 'Status dikembalikan ke PROSES (Revisi).';
        } elseif ($action == 'update') {
            // Cek status terakhir di database
            $existing = BrandingStatus::where('request_id', $reqId)->first();
            // Jika sebelumnya sudah SELESAI, biarkan SELESAI (kecuali direvisi manual)
            if ($existing && $existing->status_pekerjaan == 'SELESAI') {
                $statusPekerjaan = 'SELESAI';
            }
            $msg = 'Perubahan data berhasil disimpan.';
        }

        // --- C. SIMPAN / UPDATE DATA STATUS ---
        
        try {
            BrandingStatus::updateOrCreate(
                [
                    'request_id' => $reqId // Kunci Pencarian
                ],
                [
                    // DATA YANG AKAN DISIMPAN KE TABEL STATUS SAJA
                    'via'                   => 'WEB',
                    
                    'pembuatan_design'      => $request->pembuatan_design,
                    'approve_leader'        => $request->approve_leader,
                    'approve_toko'          => $request->approve_toko,
                    'konfirmasi_design'     => $request->konfirmasi_design,
                    
                    'tanggal_masuk_vendor'  => $request->tanggal_masuk_vendor,
                    'nama_vendor'           => $request->nama_vendor,
                    
                    'sj_di_terima_tasya'    => $request->sj_di_terima_tasya,
                    'po_selesai_gudang_fr'  => $request->po_selesai_gudang_fr,
                    'packing_barang_fr'     => $request->packing_barang_fr,
                    
                    // Pastikan name="..." di view/html sesuai dengan ini
                    'kirim_ke_dadap'        => $request->gudang_fr_kirim_ke_dadap, 
                    'terima_di_dadap'       => $request->barang_diterima_dadap,    
                    'kirim_ke_ekspedisi'    => $request->kirim_ke_ekspedisi,
                    
                    'nomor_resi'            => $request->nomor_resi,
                    'konfirmasi_penerimaan' => $request->konfirmasi_penerimaan_barang, 
                    
                    'status_pekerjaan'      => $statusPekerjaan,
                ]
            );

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