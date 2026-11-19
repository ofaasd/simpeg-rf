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
        Schema::create('tbl_khotimin', function (Blueprint $table) {
            $table->id();
            $table->string('kode_santri', 50)->comment('Kode santri / no_induk dari tabel santri_detail');
            $table->integer('santri_id')->nullable()->comment('ID santri dari tabel santri_detail');
            $table->integer('id_tahun_ajaran')->comment('ID Tahun Ajaran dari tabel ref_tahun_ajaran');
            $table->date('tanggal_khatam')->comment('Tanggal santri khatam');
            $table->string('kelas', 10)->comment('Kelas santri saat khatam misal 1A / 1B');
            $table->text('keterangan')->nullable()->comment('Catatan / keterangan tambahan');
            $table->timestamps();

            // Indexing
            $table->index('kode_santri');
            $table->index('santri_id');
            $table->index('id_tahun_ajaran');
            $table->index('kelas');
            $table->index('tanggal_khatam');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_khotimin');
    }
};
