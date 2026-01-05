<?php

namespace App\Exports;

use App\Models\BrandingStatus;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class userStatusBrandingExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $startDate;
    protected $endDate;
    protected $regions;

    public function __construct($startDate, $endDate, $regions)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
        $this->regions   = $regions; // Array regional user (contoh: ['JB', 'JR'])
    }

    public function collection()
    {
        // AMBIL SEMUA DATA STATUS BERDASARKAN FILTER
        return BrandingStatus::with('parent_data')
            ->whereHas('parent_data', function ($query) {
                // 1. Filter Tanggal (Submission Date)
                $query->whereBetween('submission_date', [$this->startDate, $this->endDate]);
                
                // 2. Filter Regional (Sesuai Hak Akses User)
                $query->whereIn('area_sales', $this->regions);
            })
            ->get();
    }

    // JUDUL KOLOM EXCEL (LENGKAP)
    public function headings(): array
    {
        return [
            'TGL REQUEST', 'ID REQUEST', 'SALES', 'TOKO', 'AREA', 
            'BRAND', 'TOOLS', 'QTY', 'UKURAN', 'KETERANGAN',
            
            // STATUS DESIGN
            'DRAFT DESIGN', 'ACC LEADER', 'ACC TOKO', 'KONF. DESIGN',
            
            // STATUS VENDOR
            'MASUK VENDOR', 'NAMA VENDOR',
            
            // LOGISTIK
            'TERIMA TASYA', 'SELESAI GUDANG', 'PACKING', 'KIRIM DADAP', 'TERIMA DADAP', 'KIRIM EKSPEDISI',
            
            // RESI
            'NO RESI', 'TGL TERIMA TOKO'
        ];
    }

    // ISI DATA PER BARIS
    public function map($item): array
    {
        $req = $item->parent_data;

        return [
            // DATA REQUEST
            $req->submission_date,
            $item->request_id,
            $req->nama_sales,
            $req->nama_toko,
            $req->area_sales,
            $req->brand,
            $req->jenis_tools_branding,
            $req->qty_tools,
            $req->ukuran_tools,
            $req->keterangan,

            // STATUS DESIGN
            $item->pembuatan_design,
            $item->approve_leader,
            $item->approve_toko,
            $item->konfirmasi_design,

            // STATUS VENDOR
            $item->tanggal_masuk_vendor,
            $item->nama_vendor,

            // LOGISTIK
            $item->sj_di_terima_tasya,
            $item->po_selesai_gudang_fr,
            $item->packing_barang_fr,
            $item->kirim_ke_dadap,
            $item->terima_di_dadap,
            $item->kirim_ke_ekspedisi,

            // RESI
            $item->nomor_resi,
            $item->konfirmasi_penerimaan,
        ];
    }

    // STYLE HEADER BOLD
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}