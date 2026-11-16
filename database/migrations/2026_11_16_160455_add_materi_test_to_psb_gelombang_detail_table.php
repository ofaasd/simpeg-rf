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
        if (!Schema::hasColumn('psb_gelombang_detail', 'materi_test')) {
            Schema::table('psb_gelombang_detail', function (Blueprint $table) {
                $table->longText('materi_test')->nullable()->after('prosedur_offline');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('psb_gelombang_detail', function (Blueprint $table) {
            $table->dropColumn('materi_test');
        });
    }
};
