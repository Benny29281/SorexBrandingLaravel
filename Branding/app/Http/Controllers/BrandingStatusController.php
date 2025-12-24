<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BrandingStatus;
use App\Models\BrandingRequest;   // Model Regional 1 (BS)
use App\Models\BrandingRequest2;  // Model Regional 2 (RB)

class BrandingStatusController extends Controller
{
    public function index(Request $request)
    {
        // 1. Get Search Input
        $search = trim($request->input('search'));

        // =========================================================================
        //                          REGIONAL 1 (BS...)
        // =========================================================================
        
        // Get valid IDs for Regional 1
        $scopeReg1 = BrandingRequest::pluck('request_id')->toArray();

        // ---------------- [A] REGIONAL 1 - PROSES ----------------
        $q1_proses = BrandingStatus::query()
            ->whereIn('request_id', $scopeReg1)       // MUST be Regional 1
            ->where('status_pekerjaan', 'PROSES');    // MUST be Process

        // Apply Search Filter (Grouped Logic)
        if ($search) {
            $q1_proses->where(function($q) use ($search) {
                $q->where('request_id', 'LIKE', "%{$search}%")
                  ->orWhere('nama_vendor', 'LIKE', "%{$search}%")
                  ->orWhere('nomor_resi', 'LIKE', "%{$search}%");
                
                // Search in Parent Table (BrandingRequest)
                $idsParent = BrandingRequest::where('nama_toko', 'LIKE', "%{$search}%")
                                            ->orWhere('nama_sales', 'LIKE', "%{$search}%")
                                            ->pluck('request_id');
                $q->orWhereIn('request_id', $idsParent);
            });
        }
        
        $data1_proses = $q1_proses->latest()
                                  ->paginate(10, ['*'], 'p1_pro')
                                  ->appends($request->all());

        // ---------------- [B] REGIONAL 1 - SELESAI ----------------
        $q1_selesai = BrandingStatus::query()
            ->whereIn('request_id', $scopeReg1)
            ->where('status_pekerjaan', 'SELESAI');

        if ($search) {
            $q1_selesai->where(function($q) use ($search) {
                $q->where('request_id', 'LIKE', "%{$search}%")
                  ->orWhere('nama_vendor', 'LIKE', "%{$search}%")
                  ->orWhere('nomor_resi', 'LIKE', "%{$search}%");
                
                $idsParent = BrandingRequest::where('nama_toko', 'LIKE', "%{$search}%")
                                            ->orWhere('nama_sales', 'LIKE', "%{$search}%")
                                            ->pluck('request_id');
                $q->orWhereIn('request_id', $idsParent);
            });
        }

        $data1_selesai = $q1_selesai->latest()
                                    ->paginate(10, ['*'], 'p1_done')
                                    ->appends($request->all());

        // Attach Parent Data Manual Loop (Reg 1)
        foreach ($data1_proses as $item) {
            $item->setRelation('parent_data', BrandingRequest::where('request_id', $item->request_id)->first());
        }
        foreach ($data1_selesai as $item) {
            $item->setRelation('parent_data', BrandingRequest::where('request_id', $item->request_id)->first());
        }


        // =========================================================================
        //                          REGIONAL 2 (RB...)
        // =========================================================================

        // Get valid IDs for Regional 2
        $scopeReg2 = BrandingRequest2::pluck('request_id')->toArray();

        // ---------------- [C] REGIONAL 2 - PROSES ----------------
        $q2_proses = BrandingStatus::query()
            ->whereIn('request_id', $scopeReg2)
            ->where('status_pekerjaan', 'PROSES');

        if ($search) {
            $q2_proses->where(function($q) use ($search) {
                $q->where('request_id', 'LIKE', "%{$search}%")
                  ->orWhere('nama_vendor', 'LIKE', "%{$search}%")
                  ->orWhere('nomor_resi', 'LIKE', "%{$search}%");
                
                // Search in Parent Table (BrandingRequest2)
                $idsParent2 = BrandingRequest2::where('nama_toko', 'LIKE', "%{$search}%")
                                              ->orWhere('nama_sales', 'LIKE', "%{$search}%")
                                              ->pluck('request_id');
                $q->orWhereIn('request_id', $idsParent2);
            });
        }

        $data2_proses = $q2_proses->latest()
                                  ->paginate(10, ['*'], 'p2_pro')
                                  ->appends($request->all());

        // ---------------- [D] REGIONAL 2 - SELESAI ----------------
        $q2_selesai = BrandingStatus::query()
            ->whereIn('request_id', $scopeReg2)
            ->where('status_pekerjaan', 'SELESAI');

        if ($search) {
            $q2_selesai->where(function($q) use ($search) {
                $q->where('request_id', 'LIKE', "%{$search}%")
                  ->orWhere('nama_vendor', 'LIKE', "%{$search}%")
                  ->orWhere('nomor_resi', 'LIKE', "%{$search}%");
                
                $idsParent2 = BrandingRequest2::where('nama_toko', 'LIKE', "%{$search}%")
                                              ->orWhere('nama_sales', 'LIKE', "%{$search}%")
                                              ->pluck('request_id');
                $q->orWhereIn('request_id', $idsParent2);
            });
        }

        $data2_selesai = $q2_selesai->latest()
                                    ->paginate(10, ['*'], 'p2_done')
                                    ->appends($request->all());

        // Attach Parent Data Manual Loop (Reg 2)
        foreach ($data2_proses as $item) {
            $item->setRelation('parent_data', BrandingRequest2::where('request_id', $item->request_id)->first());
        }
        foreach ($data2_selesai as $item) {
            $item->setRelation('parent_data', BrandingRequest2::where('request_id', $item->request_id)->first());
        }

        // =========================================================================
        // 3. SEND TO VIEW
        // =========================================================================
        return view('admin.status.index', compact(
            'data1_proses', 
            'data1_selesai', 
            'data2_proses', 
            'data2_selesai'
        ));
    }
}