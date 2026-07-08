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

    protected $headerMap = [
        'request_id'            => 'REQUEST ID',
        'submission_date'       => 'TGL REQUEST',
        'nama_sales'            => 'SALES',
        'nama_toko'             => 'TOKO',
        'lokasi'                => 'LOKASI',
        'area_sales'            => 'AREA',
        'brand'                 => 'TIPE (B/P)',
        'via'                   => 'VIA',
        'jenis_tools_branding'  => 'PERMINTAAN',
        'qty_tools'             => 'QTY',
        'pembuatan_design'      => 'PEMBUATAN DESIGN',
        'approve_leader'        => 'APPROVE LEADER',
        'approve_toko'          => 'APPROVE TOKO',
        'konfirmasi_design'     => 'KONFIRMASI DESIGN',
        'tanggal_masuk_vendor'  => 'MASUK VENDOR',
        'nama_vendor'           => 'NAMA VENDOR',
        'sj_di_terima_tasya'    => 'SJ DI TERIMA TASYA',
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
        // Pastikan $area selalu array untuk memudahkan pencarian
        $this->area = is_array($area) ? $area : ($area == 'all' ? [] : [$area]);
        $this->startDate = $startDate; 
        $this->endDate = $endDate;    
        $this->columns = $columns;
    }

    public function collection()
    {
        $ids_reg1 = [];
        $ids_reg2 = [];

        // Identifikasi kode area per regional
        $reg1_codes = ['JT', 'DK', 'LP'];
        $reg2_codes = ['JB', 'JR'];

        // Cek apakah user memilih area dari Regional 1 atau pilih semua
        $selected_reg1 = empty($this->area) ? $reg1_codes : array_intersect($this->area, $reg1_codes);
        if (!empty($selected_reg1)) {
            $q1 = BrandingRequest::query();
            $q1->whereIn('area_sales', $selected_reg1);
            
            if ($this->startDate && $this->endDate) {
                $q1->whereBetween('submission_date', [$this->startDate, $this->endDate]);
            }
            $ids_reg1 = $q1->pluck('request_id')->toArray();
        }

        // Cek apakah user memilih area dari Regional 2 (Termasuk JB)
        $selected_reg2 = empty($this->area) ? $reg2_codes : array_intersect($this->area, $reg2_codes);
        if (!empty($selected_reg2)) {
            $q2 = BrandingRequest2::query();
            $q2->whereIn('area_sales', $selected_reg2);
            
            if ($this->startDate && $this->endDate) {
                $q2->whereBetween('submission_date', [$this->startDate, $this->endDate]);
            }
            $ids_reg2 = $q2->pluck('request_id')->toArray();
        }

        $finalIds = array_unique(array_merge($ids_reg1, $ids_reg2));

        if (empty($finalIds)) {
            return collect([]);
        }

        // Ambil Data Status dengan Eager Loading agar tidak berat (N+1 Query)
        $data = BrandingStatus::whereIn('request_id', $finalIds)->latest()->get();

        return $data->map(function ($item) {
            // Logika penentuan parent berdasarkan prefix ID
            // JB sekarang masuk ke BrandingRequest2 sesuai permintaan sebelumnya
            if (str_starts_with($item->request_id, 'JB') || str_starts_with($item->request_id, 'JR')) {
                $item->parent_data = BrandingRequest2::where('request_id', $item->request_id)->first();
            } else {
                $item->parent_data = BrandingRequest::where('request_id', $item->request_id)->first();
            }
            return $item;
        })->filter(fn($item) => $item->parent_data !== null);
    }

    public function map($item): array
    {
        $p = $item->parent_data;
        $row = [];

        foreach ($this->columns as $col) {
            $val = '-';

            // List kolom yang ada di tabel parent (BrandingRequest/2)
            $parentCols = ['submission_date', 'nama_toko', 'area_sales', 'nama_sales', 'brand', 'jenis_tools_branding', 'qty_tools', 'via', 'lokasi'];
            
            if (in_array($col, $parentCols)) {
                $val = $p ? $p->{$col} : '-';
            } else {
                $val = $item->{$col};
            }

            // Format Tanggal Otomatis
            if ($val && $val != '-' && $this->isDateColumn($col)) {
                 try { $val = Carbon::parse($val)->format('d-m-Y'); } catch (\Exception $e) { }
            }

            $row[] = $val;
        }

        return $row;
    }

    // Helper untuk cek apakah kolom tersebut berisi tanggal
    private function isDateColumn($col) {
        $keywords = ['date', 'tanggal', 'pembuatan', 'approve', 'konfirmasi', 'sj_', 'po_', 'kirim_', 'terima_'];
        foreach ($keywords as $key) {
            if (str_contains($col, $key)) return true;
        }
        return false;
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