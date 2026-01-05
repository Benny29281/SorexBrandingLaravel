<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); 
            $table->string('action_type');     
            
            $table->string('request_id');           
            $table->string('nama_toko')->nullable();
            $table->string('jenis_tools_branding')->nullable();
            $table->string('ukuran_tools_branding')->nullable();
            $table->integer('qty_tools')->nullable();
            $table->text('keterangan_tambahan')->nullable();

            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
