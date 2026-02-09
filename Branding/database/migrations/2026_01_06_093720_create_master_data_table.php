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
        Schema::create('master_data', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // Contoh: "Pak Budi", "Neonbox"
            $table->string('type');           // Isi: 'SALES', 'SPV', 'TOOL'
            $table->string('area')->nullable(); // Isi: 'JT', 'DK' (Null jika Tool)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_data');
    }
};
