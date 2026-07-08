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
            
            $table->datetime('pembuatan_design')->nullable();
            $table->datetime('approve_leader')->nullable();
            $table->datetime('approve_toko')->nullable();
            $table->datetime('konfirmasi_design')->nullable();
            
            $table->datetime('tanggal_masuk_vendor')->nullable();
            $table->string('nama_vendor')->nullable();
            
            $table->datetime('sj_di_terima_tasya')->nullable();
            $table->datetime('po_selesai_gudang_fr')->nullable();
            $table->string('packing_barang_fr')->nullable();
            
            // Perhatikan nama kolom ini disamakan dengan controller Anda:
            $table->datetime('kirim_ke_dadap')->nullable(); 
            $table->datetime('terima_di_dadap')->nullable(); 
            $table->datetime('kirim_ke_ekspedisi')->nullable();
            
            $table->string('nomor_resi')->nullable();
            $table->datetime('konfirmasi_penerimaan')->nullable(); // Disamakan dengan controller
            $table->string('updated_by_role')->nullable()->after('status_pekerjaan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('branding_statuses');
    }
};