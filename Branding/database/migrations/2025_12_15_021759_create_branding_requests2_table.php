<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('branding_requests2', function (Blueprint $table) {
            $table->id(); // ID System (Auto Increment 1,2,3)
            
            // Kolom Data Sesuai Excel
            $table->date('submission_date')->nullable();
            $table->string('request_id')->nullable(); 
            $table->string('email_address')->nullable();
            $table->string('area_sales')->nullable();
            $table->string('nama_sales')->nullable(); 
            $table->string('nama_spv')->nullable();
            $table->text('nama_toko')->nullable();
            $table->text('lokasi')->nullable();
            
            $table->string('jenis_permintaan')->nullable();
            $table->string('request_type')->nullable(); 
            
            $table->string('jenis_tools_branding')->nullable();
            $table->text('ukuran_tools_branding')->nullable(); 
            $table->teks('qty_tools')->nullable();

            $table->string('brand')->nullable();
            $table->string('pengiriman')->nullable();
            $table->text('keterangan_tambahan')->nullable();
            
            $table->string('photo_area_pemasangan')->nullable();
            $table->string('photo_sugest_design')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branding_requests2');
    }
};
