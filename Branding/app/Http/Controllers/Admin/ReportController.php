<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BrandingStatusExport;
use Carbon\Carbon;

class ReportController extends Controller
{
    // 1. Tampilkan Halaman Laporan
    public function index()
    {
        return view('admin.laporan.index');
    }

    // 2. Proses Download Excel
    public function export(Request $request)
    {
        // Validasi
        $request->validate([
            'area'       => 'required',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'columns'    => 'nullable|array' // Wajib array karena checkbox
        ]);

        $area  = $request->area;
        $start = $request->start_date;
        $end   = $request->end_date;
        
        // Ambil kolom yang dipilih user
        $columns = $request->input('columns');

        // Jika user lupa centang semua, kita kasih default minimal agar tidak error
        if (empty($columns)) {
            $columns = ['request_id', 'nama_toko', 'status_pekerjaan']; 
        }

        // Nama File Cantik
        $timestamp = Carbon::now()->format('d-m-Y_H-i');
        $fileName  = "Laporan_Branding_{$area}_{$timestamp}.xlsx";

        // Kirim $columns ke Export Class (Parameter ke-4)
        return Excel::download(new BrandingStatusExport($area, $start, $end, $columns), $fileName);
    }
}