<?php

namespace App\Exports;

use App\Models\BrandingStatus;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StatusBrandingExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $startDate;
    protected $endDate;
    protected $regions;

    public function __construct($startDate, $endDate, $regions)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
        $this->regions   = $regions;
    }

    public function collection()
    {
        // KITA HARUS CEK KE DUA TABEL PARENT (REG1 & REG2)
        return BrandingStatus::query()
            // Kita load dua-duanya biar cepat
            ->with(['parent_data_reg1', 'parent_data_reg2']) 
            
            ->where(function ($query) {
                
                // 1. Cek Filter di Tabel Regional 1 (BS)
                $query->whereHas('parent_data_reg1', function ($q) {
                    $q->whereBetween('submission_date', [$this->startDate, $this->endDate])
                      ->whereIn('area_sales', $this->regions);
                })
                
                // 2. ATAU Cek Filter di Tabel Regional 2 (RB)
                ->orWhereHas('parent_data_reg2', function ($q) {
                    $q->whereBetween('submission_date', [$this->startDate, $this->endDate])
                      ->whereIn('area_sales', $this->regions);
                });

            })
            ->get();
    }

    public function headings(): array
    {
        return [
            'TGL REQUEST', 'ID REQUEST', 'SALES', 'TOKO', 'AREA', 
            'BRAND', 'TOOLS', 'QTY',
            'PEMBUATAN DESIGN', 'ACC LEADER', 'ACC TOKO', 'KONFIRMASI DESIGN',
            'MASUK VENDOR', 'NAMA VENDOR',
            'SJ DI TERIMA TASYA', 'PO SELESAI & KEGUDANG FR', 'PACKING DI GUDANG FR', 'KIRIM KE DADAP', 'TERIMA DI DADAP', 'KIRIM EKSPEDISI',
            'NO RESI', 'TGL TERIMA TOKO'
        ];
    }

    public function map($item): array
    {
        // AMBIL DATA PARENT (Otomatis pilih Reg1 atau Reg2 lewat Accessor Anda)
        // Accessor 'parent_data' yang Anda buat di Model sudah benar untuk bagian ini.
        $req = $item->parent_data; 

        // Jaga-jaga jika data parent terhapus / tidak ketemu
        if (!$req) {
            return []; 
        }

        $formatDT = function($date) {
        return $date ? \Carbon\Carbon::parse($date)->format('d-m-Y H:i:s') : '-';
    };

        return [
            $req->submission_date,
            $item->request_id,
            $req->nama_sales,
            $req->nama_toko,
            $req->area_sales,
            $req->brand,
            $req->jenis_tools_branding,
            $req->qty_tools,
            $item->pembuatan_design,
            $item->approve_leader,
            $item->approve_toko,
            $item->konfirmasi_design,
            $item->tanggal_masuk_vendor,
            $item->nama_vendor,
            $item->sj_di_terima_tasya,
            $item->po_selesai_gudang_fr,
            $item->packing_barang_fr,
            $item->kirim_ke_dadap,
            $item->terima_di_dadap,
            $item->kirim_ke_ekspedisi,
            $item->nomor_resi,
            $item->konfirmasi_penerimaan,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [ 1 => ['font' => ['bold' => true]] ];
    }
}