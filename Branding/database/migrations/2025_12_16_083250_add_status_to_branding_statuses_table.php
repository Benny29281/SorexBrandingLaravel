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
            Schema::table('branding_statuses', function (Blueprint $table) {
                // Default 'PROSES'. Nanti bisa diubah jadi 'SELESAI'
                $table->string('status_pekerjaan')->default('PROSES')->after('via'); 
            });
        }

        public function down()
        {
            Schema::table('branding_statuses', function (Blueprint $table) {
                $table->dropColumn('status_pekerjaan');
            });
        }
};
