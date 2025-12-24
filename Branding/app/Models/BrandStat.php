<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandStat extends Model
{
    use HasFactory;

    protected $table = 'brand_stats';

    protected $fillable = [
        'label',
        'value',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}
