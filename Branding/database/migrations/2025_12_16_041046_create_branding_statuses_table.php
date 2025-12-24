<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('branding_statuses', function (Blueprint $table) {
            $table->id();
            
            // KUNCI PENGHUBUNG
            $table->string('request_id')->unique(); 

            // DATA SESUAI CONTROLLER ANDA
            $table->string('tipe_bp')->nullable();
            $table->string('via')->nullable();
            
            $table->date('pembuatan_design')->nullable();
            $table->date('approve_leader')->nullable();
            $table->date('approve_toko')->nullable();
            $table->date('konfirmasi_design')->nullable();
            
            $table->date('tanggal_masuk_vendor')->nullable();
            $table->string('nama_vendor')->nullable();
            
            $table->date('sj_di_terima_tasya')->nullable();
            $table->date('po_selesai_gudang_fr')->nullable();
            $table->string('packing_barang_fr')->nullable();
            
            // Perhatikan nama kolom ini disamakan dengan controller Anda:
            $table->date('kirim_ke_dadap')->nullable(); 
            $table->date('terima_di_dadap')->nullable(); 
            $table->date('kirim_ke_ekspedisi')->nullable();
            
            $table->string('nomor_resi')->nullable();
            $table->date('konfirmasi_penerimaan')->nullable(); // Disamakan dengan controller

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('branding_statuses');
    }
};