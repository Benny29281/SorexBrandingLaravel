<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterData extends Model
{
    use HasFactory;

    // --- TAMBAHKAN BAGIAN INI ---
    // Ini memberi izin agar kolom name, type, dan area bisa diisi data.
    protected $fillable = [
        'name',
        'type',
        'area',
    ];
}