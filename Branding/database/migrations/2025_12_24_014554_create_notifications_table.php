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
    Schema::create('notifications', function (Blueprint $table) {
        $table->id();
        $table->string('type');      // Jenis notif (INPUT/REVISI)
        $table->string('title');     // Judul
        $table->text('message');     // Pesan lengkap
        $table->string('url')->nullable(); // Link tujuan saat diklik
        $table->boolean('is_read')->default(false); // Status baca
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
