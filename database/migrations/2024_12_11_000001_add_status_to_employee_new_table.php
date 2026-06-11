<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employee_new', function (Blueprint $table) {
            $table->tinyInteger('status')->default(1)->after('lembaga_diikuti')->comment('0=tidak aktif, 1=aktif, 2=cuti, 3=keluar, 4=dikeluarkan, 5=meninggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_new', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
