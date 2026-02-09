<?php

namespace App\Imports;

use App\Models\BrandingStatus;
use App\Models\BrandingRequest;  // Regional 1
use App\Models\BrandingRequest2; // Regional 2
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class BrandingStatusImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // 1. Validasi ID
        if (!isset($row['request_kode']) || $row['request_kode'] == null) {
            return null;
        }

        $reqId = trim($row['request_kode']); 

        // STEP 1: FILTER AREA SALES (JT, DK, LP, JB, JR AJA)
        
        $excelArea = strtoupper(trim($row['area'] ?? '')); 
        $allowedCodes = ['JT', 'DK', 'LP', 'JB', 'JR'];   

        $finalArea = '-';
        $finalLokasi = '-';

        // Cek: Apakah isi Excel ada di dalam daftar kode yang diizinkan?
        if (in_array($excelArea, $allowedCodes)) {
            
            $finalArea = $excelArea;
            $finalLokasi = $row['lokasi'] ?? '-'; 
        } else {
            
            $finalArea = '-'; 
            $finalLokasi = $row['area']; 
        }

        // STEP 2: DATA INDUK
        
        $parentData = [
            'submission_date'       => $this->transformDate($row['tanggal_request'] ?? null) ?? now(),
            'nama_sales'            => $row['nama_sales'] ?? '-',
            'nama_toko'             => $row['nama_toko'] ?? 'Toko Import',
            
            // HASIL FILTER DI ATAS
            'area_sales'            => $finalArea,   
            'lokasi'                => $finalLokasi, 

            'brand'                 => 'SOREX',
            'jenis_permintaan'      => $row['tipe_b_p'] ?? 'BARU',
            'jenis_tools_branding'  => $row['permintaan'] ?? '-',
            'qty_tools'             => $row['qty'] ?? 1,
            'status'                => 'DRAFT', 
            'via'                   => $row['s'] ?? 'EXCEL',
            'email_address'         => '-',
            'nama_spv'              => '-',
        ];

        // =================================================================
        // STEP 3: ROUTER (BS/JT/DK/LP -> Reg 1, RB/JB/JR -> Reg 2)
        // =================================================================
        
        $prefix = strtoupper(substr($reqId, 0, 2));

        // GRUP REGIONAL 1
        if (in_array($prefix, ['BS', 'JT', 'DK', 'LP'])) {
            BrandingRequest::updateOrCreate(['request_id' => $reqId], $parentData);
        } 
        // GRUP REGIONAL 2
        elseif (in_array($prefix, ['RB', 'JB', 'JR'])) {
            BrandingRequest2::updateOrCreate(['request_id' => $reqId], $parentData);
        }

        // =================================================================
        // STEP 4: SIMPAN STATUS TRACKING
        
        $tgl_terima = $this->transformDate($row['konfirmasi_penerimaan_barang'] ?? null);
        $statusPekerjaan = $tgl_terima ? 'SELESAI' : 'PROSES';

        return BrandingStatus::updateOrCreate(
            ['request_id' => $reqId], 
            [
                'via'                   => $row['s'] ?? 'EXCEL',
                'ukuran_fix'            => $row['ukuran_asli'] ?? $row['ukuran'] ?? null,
                'pembuatan_design'      => $this->transformDate($row['pembuatan_design'] ?? null),
                'approve_leader'        => $this->transformDate($row['approve_leader'] ?? null),
                'approve_toko'          => $this->transformDate($row['approve_toko'] ?? null),
                'konfirmasi_design'     => $this->transformDate($row['konfirmasi_design'] ?? null),
                'tanggal_masuk_vendor'  => $this->transformDate($row['tanggal_masuk_vendor'] ?? null),
                'nama_vendor'           => $row['nama_vendor'] ?? null, 
                'sj_di_terima_tasya'    => $this->transformDate($row['sj_di_terima_tasya'] ?? null),
                'po_selesai_gudang_fr'  => $this->transformDate($row['po_selesai_dikirim_ke_gudang_fr'] ?? $row['po_selesai_ke_gudang_fr'] ?? null),
                'packing_barang_fr'     => $row['packing_barang_fr'] ?? null,
                'kirim_ke_dadap'        => $this->transformDate($row['gudang_fr_kirim_ke_dadap'] ?? null),
                'terima_di_dadap'       => $this->transformDate($row['barang_di_terima_dadap'] ?? null),
                'kirim_ke_ekspedisi'    => $this->transformDate($row['kirim_ke_ekspedisi'] ?? null),
                'nomor_resi'            => $row['nomor_resi'] ?? $row['resi'] ?? null, 
                'konfirmasi_penerimaan' => $tgl_terima,
                'status_pekerjaan'      => $statusPekerjaan,
            ]
        );
    }

    // HELPER TANGGAL
    private function transformDate($value)
    {
        if (empty($value) || $value == '-' || $value == 'NULL') return null;
        try {
            $dateString = null;
            if (is_numeric($value)) {
                $dateString = Date::excelToDateTimeObject($value)->format('Y-m-d');
            } else {
                $value = trim($value);
                $bulanIndo = ['OKTOBER', 'MEI', 'AGUSTUS', 'DESEMBER', 'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'JUNI', 'JULI', 'SEPTEMBER', 'NOVEMBER'];
                $bulanInggris = ['OCTOBER', 'MAY', 'AUGUST', 'DECEMBER', 'JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'JUNE', 'JULY', 'SEPTEMBER', 'NOVEMBER'];
                $value = str_ireplace($bulanIndo, $bulanInggris, $value);
                $value = str_replace('/', '-', $value);
                $timestamp = strtotime($value);
                if ($timestamp) $dateString = date('Y-m-d', $timestamp);
            }

            if ($dateString) {
                $parts = explode('-', $dateString);
                $year = (int) $parts[0]; 
                if ($year == 20204) { $parts[0] = '2024'; $dateString = implode('-', $parts); $year = 2024; }
                if ($year < 2000 || $year > 2030) return null; 
            }
            return $dateString;
        } catch (\Exception $e) { return null; }
    }
}