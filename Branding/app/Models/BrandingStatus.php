<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandingStatus extends Model
{
    use HasFactory;

    protected $guarded = [];

    // BrandingStatus.php

        public function getParentDataAttribute()
        {
            $prefix = strtoupper(substr($this->request_id, 0, 2));

            if ($prefix === 'BS') {
                // Gunakan getRelationValue agar mengambil dari eager load 'parent_data_reg1'
                return $this->parent_data_reg1; 
            } 
            
            if ($prefix === 'RB') {
                return $this->parent_data_reg2;
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