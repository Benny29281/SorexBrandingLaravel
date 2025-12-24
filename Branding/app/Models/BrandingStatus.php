<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandingStatus extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getParentDataAttribute()
    {
        // 1. Ambil 2 Huruf pertama dari ID (Misal: BS atau RB)
        $prefix = strtoupper(substr($this->request_id, 0, 2));

        // 2. Jika BS -> Cari di Tabel Regional 1 (BrandingRequest)
        if ($prefix === 'BS') {
            return \App\Models\BrandingRequest::where('request_id', $this->request_id)->first();
        } 
        
        // 3. Jika RB -> Cari di Tabel Regional 2 (BrandingRequest2)
        elseif ($prefix === 'RB') {
            return \App\Models\BrandingRequest2::where('request_id', $this->request_id)->first();
        }

        return null;
    }

        public function parent_data_reg1()
        {
            // Asumsi 'request_id' adalah kunci penghubung
            return $this->belongsTo(BrandingRequest::class, 'request_id', 'request_id');
        }

        public function parent_data_reg2()
        {
            return $this->belongsTo(BrandingRequest2::class, 'request_id', 'request_id');
        }
}