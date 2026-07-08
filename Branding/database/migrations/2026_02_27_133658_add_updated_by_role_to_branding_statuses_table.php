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
            // Kolom ini akan menyimpan 'Admin', 'Design', dll sesuai login user
            $table->string('updated_by_role')->nullable()->after('status_pekerjaan');
        });
    }

    public function down()
    {
        Schema::table('branding_statuses', function (Blueprint $table) {
            $table->dropColumn('updated_by_role');
        });
    }
};
