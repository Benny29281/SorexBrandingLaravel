<?php

namespace App\Exports;

use App\Models\BrandingStatus;
use App\Models\BrandingRequest;
use App\Models\BrandingRequest2;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class BrandingStatusExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $area;
    protected $startDate;
    protected $endDate;
    protected $columns;

    // MAPPING HEADER SESUAI GAMBAR ANDA (JANGAN DIUBAH URUTAN DI SINI TIDAK MASALAH, KARENA MENGIKUTI INPUT CHECKBOX)
    protected $headerMap = [
        // --- HEADER 1 (HITAM) ---
        'request_id'            => 'REQUEST ID',
        'submission_date'       => 'TGL REQUEST',
        'nama_sales'            => 'SALES',
        'nama_toko'             => 'TOKO',
        'area_sales'            => 'AREA',
        'brand'                 => 'TIPE (B/P)', // Saya mapping Brand ke TIPE (B/P) sesuai request
        'via'                   => 'VIA',
        'jenis_tools_branding'  => 'PERMINTAAN',
        'qty_tools'             => 'QTY',

        // --- HEADER 2 (BIRU / COKLAT) ---
        'pembuatan_design'      => 'PEMBUATAN DESIGN',
        'approve_leader'        => 'APPROVE LEADER',
        'approve_toko'          => 'APPROVE TOKO',
        'konfirmasi_design'     => 'KONFIRMASI DESIGN',
        'tanggal_masuk_vendor'  => 'MASUK VENDOR',
        'nama_vendor'           => 'NAMA VENDOR',
        'sj_di_terima_tasya'    => 'SJ DI TERIMA TASYA',

        // --- HEADER 3 (HIJAU / UNGU) ---
        'po_selesai_gudang_fr'  => 'PO SELESAI & KE GUDANG FR',
        'packing_barang_fr'     => 'PACKING DI GUDANG FR',
        'kirim_ke_dadap'        => 'KIRIM KE DADAP',
        'terima_di_dadap'       => 'TERIMA DI DADAP',
        'kirim_ke_ekspedisi'    => 'KIRIM KE EKSPEDISI',
        'nomor_resi'            => 'NOMOR RESI',
        'konfirmasi_penerimaan' => 'KONF. PENERIMAAN'
    ];

    public function __construct($area, $startDate, $endDate, $columns)
    {
        $this->area = $area;
        $this->startDate = $startDate; 
        $this->endDate = $endDate;    
        $this->columns = $columns;
    }

    public function collection()
    {
        $ids_reg1 = [];
        $ids_reg2 = [];

        // 1. REGIONAL 1
        if ($this->area == 'all' || in_array($this->area, ['JT', 'DK', 'LP'])) {
            $q1 = BrandingRequest::query();
            if ($this->area != 'all') {
                $q1->where(function($q) {
                    $q->where('area_sales', $this->area)
                      ->orWhere('nama_sales', 'LIKE', $this->area.'%')
                      ->orWhere('request_id', 'LIKE', $this->area.'%');
                });
            }
            if ($this->startDate && $this->endDate) {
                $q1->whereBetween('submission_date', [$this->startDate, $this->endDate]);
            }
            $ids_reg1 = $q1->pluck('request_id')->toArray();
        }

        // 2. REGIONAL 2
        if ($this->area == 'all' || in_array($this->area, ['JB', 'JR'])) {
            $q2 = BrandingRequest2::query();
            if ($this->area != 'all') {
                $q2->where(function($q) {
                    $q->where('area_sales', $this->area)
                      ->orWhere('nama_sales', 'LIKE', $this->area.'%')
                      ->orWhere('request_id', 'LIKE', $this->area.'%');
                });
            }
            if ($this->startDate && $this->endDate) {
                $q2->whereBetween('submission_date', [$this->startDate, $this->endDate]);
            }
            $ids_reg2 = $q2->pluck('request_id')->toArray();
        }

        // 3. GABUNGKAN
        $finalIds = array_merge($ids_reg1, $ids_reg2);

        if (empty($finalIds)) {
            return collect([]);
        }

        // Ambil Data Status
        $data = BrandingStatus::whereIn('request_id', $finalIds)->latest()->get();

        // Filter & Attach Parent
        $filteredData = $data->filter(function ($item) {
            if (str_contains($item->request_id, 'BS') || str_contains($item->request_id, 'JT') || str_contains($item->request_id, 'DK') || str_contains($item->request_id, 'LP')) {
                $item->parent_data = BrandingRequest::where('request_id', $item->request_id)->first();
            } else {
                $item->parent_data = BrandingRequest2::where('request_id', $item->request_id)->first();
            }

            if (!$item->parent_data) return false;

            if ($this->startDate && $this->endDate) {
                try {
                    $dateDb = Carbon::parse($item->parent_data->submission_date);
                    $start = Carbon::parse($this->startDate)->startOfDay();
                    $end = Carbon::parse($this->endDate)->endOfDay();
                    return $dateDb->between($start, $end);
                } catch (\Exception $e) { return true; }
            }
            return true;
        });

        return $filteredData;
    }

    public function map($item): array
    {
        $p = $item->parent_data;
        $row = [];

        foreach ($this->columns as $col) {
            $val = '-';

            // Cek Parent
            if (in_array($col, ['submission_date', 'nama_toko', 'area_sales', 'nama_sales', 'brand', 'jenis_tools_branding', 'qty_tools', 'via'])) {
                $val = $p ? $p->{$col} : '-';
            }
            // Cek Child
            else {
                $val = $item->{$col};
            }

            // Format Tanggal
            if ($val && $val != '-' && (
                str_contains($col, 'date') || 
                str_contains($col, 'tanggal') || 
                str_contains($col, 'pembuatan') || 
                str_contains($col, 'approve') || 
                str_contains($col, 'konfirmasi') || 
                str_contains($col, 'sj_') || 
                str_contains($col, 'po_') || 
                str_contains($col, 'kirim_') || 
                str_contains($col, 'terima_')
            )) {
                 try { $val = Carbon::parse($val)->format('d-m-Y'); } catch (\Exception $e) { }
            }

            $row[] = $val;
        }

        return $row;
    }

    public function headings(): array
    {
        $headers = [];
        foreach ($this->columns as $col) {
            $headers[] = $this->headerMap[$col] ?? strtoupper(str_replace('_', ' ', $col));
        }
        return $headers;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '2d3748']]
            ],
        ];
    }
}