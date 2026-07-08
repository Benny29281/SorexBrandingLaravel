<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BrandingStatusExport;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function export(Request $request)
    {
        $request->validate([
            'area'       => 'nullable|array', 
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'columns'    => 'nullable|array'
        ]);

        $area = $request->input('area', []); // Pastikan defaultnya array kosong jika null
        
        $areaLabel = 'SEMUA';
        if (!empty($area)) {
            $areaLabel = count($area) > 2 
                ? implode('-', array_slice($area, 0, 2)) . '-dst' 
                : implode('-', $area);
        }

        $start = $request->start_date;
        $end   = $request->end_date;
        
        // --- REVISI DI SINI ---
        // Pastikan 'submission_date' dan 'request_id' selalu ikut jika tidak dipilih user,
        // agar proses sorting di Export class tetap berjalan mulus.
        $columns = $request->input('columns');
        if (empty($columns)) {
            $columns = ['request_id', 'submission_date', 'nama_toko', 'area_sales']; 
        }
        // -----------------------

        $timestamp = Carbon::now()->format('d-m-Y_H-i');
        $fileName  = "Laporan_Branding_{$areaLabel}_{$timestamp}.xlsx";

        return Excel::download(new BrandingStatusExport($area, $start, $end, $columns), $fileName);
    }
}