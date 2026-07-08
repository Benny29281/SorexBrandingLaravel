<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandingRequest extends Model
{

    use HasFactory;
    protected $table = 'branding_requests'; 

    protected $guarded = [];
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
