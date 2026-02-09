<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action_type',
        'request_id',            // Sesuai nama kolom Anda
        'nama_toko',
        'nama_sales',
        'jenis_tools_branding',  // Sesuai nama kolom Anda
        'ukuran_tools_branding', // Sesuai nama kolom Anda
        'qty_tools',             // Sesuai nama kolom Anda
        'brand', 
        'keterangan_tambahan',   // Sesuai nama kolom Anda
    ];
}