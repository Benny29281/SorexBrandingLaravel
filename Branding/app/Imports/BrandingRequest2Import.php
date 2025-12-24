<?php

namespace App\Imports;

use App\Models\BrandingRequest2;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class BrandingRequest2Import implements ToModel, WithStartRow, WithCustomCsvSettings
{
    public function startRow(): int
    {
        return 2;
    }

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'UTF-8',
            'delimiter' => ','
        ];
    }

    public function model(array $row)
    {
        // 1. Skip jika ID kosong
        if (!isset($row[1]) || $row[1] == '') {
            return null;
        }

        // 2. Formatting Tanggal
        $raw_date = $row[0];
        $submission_date = now();
        try {
            if (is_numeric($raw_date)) {
                $submission_date = Date::excelToDateTimeObject($raw_date);
            } elseif (!empty($raw_date)) {
                $submission_date = Carbon::parse($raw_date);
            }
        } catch (\Exception $e) {
            $submission_date = now();
        }

        // --- LOGIKA AUTO-FIX KOLOM GESER (PENTING) ---
        // (Agar kolom Qty/Brand tetap terbaca meski Excel berantakan)
        $idx_jenis  = 9;
        $idx_ukuran = 10;
        $idx_qty    = 11;
        $idx_brand  = 12;
        $idx_kirim  = 13;
        $idx_ket    = 14;

        // Cek apakah kolom QTY (idx 11) isinya angka? Jika tidak, geser kanan.
        $qty_check = isset($row[$idx_qty]) ? $row[$idx_qty] : 0;
        if (!is_numeric($qty_check)) {
            if (isset($row[12]) && is_numeric($row[12])) { // Geser 1
                $idx_jenis = 10; $idx_ukuran = 11; $idx_qty = 12; 
                $idx_brand = 13; $idx_kirim = 14; $idx_ket = 15;
            } elseif (isset($row[13]) && is_numeric($row[13])) { // Geser 2
                $idx_jenis = 11; $idx_ukuran = 12; $idx_qty = 13; 
                $idx_brand = 14; $idx_kirim = 15; $idx_ket = 16;
            }
        }

        // 3. UPDATE OR CREATE (SOLUSI ERROR DUPLICATE)
        // Kuncinya ada di sini: Kita cek 'request_id'.
        
        return BrandingRequest2::updateOrCreate(
            [
                'request_id' => $row[1], // <-- Cek apakah ID BS785 ini sudah ada?
            ],
            [
                
                'submission_date'       => $submission_date,
                'email_address'         => $row[2],
                'area_sales'            => $row[3],
                'nama_sales'            => $row[4],
                'nama_spv'              => $row[5],
                'nama_toko'             => $row[6],
                'lokasi'                => $row[7],
                'jenis_permintaan'      => $row[8],
                
                // Pake index dinamis hasil auto-fix
                'jenis_tools_branding'  => $row[$idx_jenis] ?? '-',
                'ukuran_tools_branding' => $row[$idx_ukuran] ?? '-',
                'qty_tools'             => (int) ($row[$idx_qty] ?? 0),
                'brand'                 => $row[$idx_brand] ?? '-',
                'pengiriman'            => $row[$idx_kirim] ?? '-',
                'keterangan_tambahan'   => $row[$idx_ket] ?? '',
                
                // Index foto ikut geser
                'photo_area_pemasangan' => $row[$idx_ket + 1] ?? null,
                'photo_sugest_design'   => $row[$idx_ket + 2] ?? null,
            ]
        );
    }
}