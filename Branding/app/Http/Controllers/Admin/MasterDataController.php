<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterData;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    /**
     * Menampilkan Halaman Manajemen Data (Dengan Fitur Filter)
     */
    public function index(Request $request)
    {
        // 1. Ambil List Area Unik untuk Dropdown Filter (Misal: JT, DK, LP, JB)
        // Kita ambil dari database agar otomatis nambah jika ada area baru
        $listArea = MasterData::whereNotNull('area')
                              ->select('area')
                              ->distinct()
                              ->orderBy('area')
                              ->pluck('area');

        // 2. Cek apakah Admin sedang memfilter?
        $filter = $request->input('filter_area');

        // 3. Siapkan Query Dasar
        $salesQuery = MasterData::where('type', 'SALES')->orderBy('area')->orderBy('name');
        $spvQuery   = MasterData::where('type', 'SPV')->orderBy('area')->orderBy('name');
        
        // Tools selalu tampil semua (karena bersifat global)
        $tools = MasterData::where('type', 'TOOL')->orderBy('name')->get(); 

        // 4. Jika ada Filter, tambahkan kondisi 'where area' pada Sales & SPV
        if ($filter) {
            $salesQuery->where('area', $filter);
            $spvQuery->where('area', $filter);
        }

        // 5. Eksekusi Query
        $sales = $salesQuery->get();
        $spvs  = $spvQuery->get();

        return view('admin.master.index', compact('sales', 'spvs', 'tools', 'listArea', 'filter'));
    }

    /**
     * Menyimpan Data Baru (Support Paket Regional & Input Satuan)
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:SALES,SPV,TOOL',
        ]);

        $inputName = strtoupper($request->name);
        $inputType = $request->type;
        $inputArea = $request->area_input; // Mengambil dari name="area_input" di form

        // --- SKENARIO 1: INPUT TOOLS (Tanpa Area) ---
        if ($inputType === 'TOOL') {
            MasterData::create([
                'name' => $inputName,
                'type' => 'TOOL',
                'area' => null,
            ]);
        } 
        
        // --- SKENARIO 2: INPUT SALES / SPV (Pakai Area) ---
        else {
            
            // Definisikan Paket Regional di sini
            $paketRegional = [
                'ALL_REG1' => ['JT', 'DK', 'LP'], // Paket Regional 1
                'ALL_REG2' => ['JB', 'JR'],       // Paket Regional 2
            ];

            // Cek: Apakah admin memilih Paket Regional?
            if (array_key_exists($inputArea, $paketRegional)) {
                
                // Jika YA, Ambil daftar areanya lalu looping simpan
                $daftarArea = $paketRegional[$inputArea];
                
                foreach ($daftarArea as $kodeArea) {
                    MasterData::create([
                        'name' => $inputName,
                        'type' => $inputType,
                        'area' => $kodeArea, // Simpan satu per satu
                    ]);
                }

            } else {
                // Jika TIDAK (Pilih satuan, misal cuma 'LP')
                // Pastikan area tidak kosong sebelum disimpan
                if (!empty($inputArea)) {
                    MasterData::create([
                        'name' => $inputName,
                        'type' => $inputType,
                        'area' => $inputArea,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Update Data (Edit Nama/Area)
     */
    public function update(Request $request, $id)
    {
        $data = MasterData::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'area' => 'nullable|string',
        ]);

        $data->update([
            'name' => strtoupper($request->name),
            'area' => $request->area ? strtoupper($request->area) : null,
        ]);

        return redirect()->back()->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Hapus Data
     */
    public function destroy($id)
    {
        $data = MasterData::findOrFail($id);
        $data->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }
}