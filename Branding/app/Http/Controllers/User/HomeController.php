<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StatusBrandingExport;

class HomeController extends Controller
{
    public function index()
    {
        return view('user.home');
    }

    public function viewDownloadPage() 
    {
        return view('user.download');
    }

    public function logAktivitas()
    {
        return view('user.log_aktivitas');
    }

    // --- FITUR DOWNLOAD DENGAN FILTER REGIONAL YANG BENAR ---
    public function downloadStatus(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'area'       => 'nullable|string' 
        ]);

        $user = Auth::user();
        $reg = strtolower($user->regional); // Huruf kecil semua

        // 2. Tentukan HAK AKSES UTAMA User (Master List)
        $masterAccess = [];
        if (str_contains($reg, '1') || str_contains($reg, 'reg1') || str_contains($reg, 'regional 1')) {
            $masterAccess = ['JT', 'DK', 'LP']; // Regional 1
        } else {
            $masterAccess = ['JB', 'JR']; // Regional 2
        }

        // 3. LOGIKA FILTER PILIHAN USER
        $finalRegions = [];
        $pilihanUser = $request->input('area');

        if ($pilihanUser && $pilihanUser !== 'ALL') {
            // SECURITY: Cek apakah user berhak mengakses area tsb?
            if (in_array($pilihanUser, $masterAccess)) {
                $finalRegions = [$pilihanUser]; // Ambil 1 area
            } else {
                $finalRegions = $masterAccess; 
            }
        } else {
            // Jika pilih ALL
            $finalRegions = $masterAccess;
        }

        // 4. Download
        $labelArea = ($pilihanUser && $pilihanUser !== 'ALL') ? $pilihanUser : 'ALL_AREA';
        
        return Excel::download(
            new StatusBrandingExport($request->start_date, $request->end_date, $finalRegions), 
            'Laporan_'.$labelArea.'_'.$request->start_date.'.xlsx'
        );
    }
}