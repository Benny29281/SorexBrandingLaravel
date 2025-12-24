<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BrandingRequest;
use App\Models\BrandingRequest2;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. FILTER TAHUN & BULAN
        $filterYear  = $request->input('year');
        $filterMonth = $request->input('month');

        $query1 = BrandingRequest::query();  // Tabel 1 (JT, DK, LP)
        $query2 = BrandingRequest2::query(); // Tabel 2 (JB, JR)

        if ($filterYear) {
            $query1->whereYear('submission_date', $filterYear);
            $query2->whereYear('submission_date', $filterYear);
        }
        if ($filterMonth) {
            $query1->whereMonth('submission_date', $filterMonth);
            $query2->whereMonth('submission_date', $filterMonth);
        }

        // 2. HITUNG TOTAL KARTU
        $total_jt_dk_lp = (clone $query1)->whereIn('area_sales', ['JT', 'DK', 'LP'])->count();
        $total_jb_jr    = (clone $query2)->whereIn('area_sales', ['JB', 'JR'])->count();
        $grand_total    = $total_jt_dk_lp + $total_jb_jr;

        // 3. AMBIL DATA UNTUK GRAFIK
        $data1 = $query1->select('submission_date', 'jenis_tools_branding', 'area_sales', 'brand')->get();
        $data2 = $query2->select('submission_date', 'jenis_tools_branding', 'area_sales', 'brand')->get();
        $allData = $data1->concat($data2); // Data Gabungan

        // --- A. DATA TREN PERMINTAAN (DIPISAH JADI 2 DATASET) ---
        $monthsOrder = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        // Helper function kecil untuk grouping per bulan
        $groupByMonth = function($dataCollection) use ($monthsOrder) {
            $counts = $dataCollection->groupBy(function($date) {
                return Carbon::parse($date->submission_date)->format('F');
            })->map->count();
            
            // Isi bulan yang kosong dengan 0 agar grafik sejajar
            $result = collect([]);
            foreach($monthsOrder as $month) {
                $result->push($counts->get($month, 0));
            }
            return $result;
        };

        $tren1Values = $groupByMonth($data1); // Data Tren JT, DK, LP
        $tren2Values = $groupByMonth($data2); // Data Tren JB, JR

        // --- B. DATA TOP TOOLS (STACKED BAR) ---
        // 1. Cari dulu 5 Tools Terpopuler secara total
        $topToolsLabels = $allData->groupBy('jenis_tools_branding')
                            ->map->count()
                            ->sortDesc()
                            ->take(10)
                            ->keys(); // Ambil nama tools-nya saja

        // 2. Hitung jumlah masing-masing tools di Area 1 dan Area 2
        $tools1Values = [];
        $tools2Values = [];

        foreach($topToolsLabels as $tool) {
            $tools1Values[] = $data1->where('jenis_tools_branding', $tool)->count();
            $tools2Values[] = $data2->where('jenis_tools_branding', $tool)->count();
        }

        // --- C. AREA SALES (SAMA SEPERTI SEBELUMNYA) ---
        $targetArea = ['JT', 'DK', 'LP', 'JB', 'JR'];
        $chartArea = $allData->whereIn('area_sales', $targetArea)->groupBy('area_sales')->map->count();

        // --- D. BRAND (SAMA SEPERTI SEBELUMNYA) ---
        $targetBrand = ['SOREX LADIES', 'SOREX MAN', 'SOREX KIDS'];
        $chartBrand = $allData->map(function ($item) {
                            $item->brand = strtoupper(trim($item->brand));
                            return $item;
                        })->whereIn('brand', $targetBrand)->groupBy('brand')->map->count();

        return view('admin.dashboard', [
            'selectedYear' => $filterYear,
            'selectedMonth'=> $filterMonth,
            'total_jt_dk_lp' => $total_jt_dk_lp,
            'total_jb_jr'    => $total_jb_jr,
            'grand_total'    => $grand_total,
            
            // Data Grafik Tren (Kirim 2 Data)
            'trenLabels' => $monthsOrder,
            'tren1' => $tren1Values,
            'tren2' => $tren2Values,

            // Data Grafik Tools (Kirim 2 Data + Label)
            'toolsLabels' => $topToolsLabels,
            'tools1' => $tools1Values,
            'tools2' => $tools2Values,

            // Data Lainnya
            'areaLabels' => $chartArea->keys(), 'areaValues' => $chartArea->values(),
            'brandLabels' => $chartBrand->keys(), 'brandValues' => $chartBrand->values(),
        ]);
    }
}