<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandingRequest2 extends Model
{
    use HasFactory;

    // 1. Definisikan nama tabel secara eksplisit
    protected $table = 'branding_requests2'; 

    protected $guarded = [];

    // Tambahkan 3 baris ini di dalam class BrandingRequest2
    protected $primaryKey = 'request_id'; // Nama kolom kunci di database Anda
    public $incrementing = false;        // Karena kodenya manual (RB1847), bukan angka otomatis
    protected $keyType = 'string';       // Karena kodenya mengandung huruf
    
    // Atau jika pakai fillable, pastikan lengkap:
    protected $fillable = [
        'submission_date',
        'request_id',
        'email_address',
        'area_sales',
        'nama_sales',
        'nama_spv',
        'nama_toko',
        'lokasi',
        'jenis_permintaan',
        'jenis_tools_branding',
        'ukuran_tools_branding',
        'qty_tools',
        'brand',
        'pengiriman',
        'keterangan_tambahan',
        'photo_area_pemasangan',
        'photo_sugest_design',
        'status',
        'updated_by',
    ];
}