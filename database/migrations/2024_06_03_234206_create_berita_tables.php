<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('berita', function (Blueprint $table) {
      $table->id();
      $table->foreignId('kategori_id');
      $table->string('judul');
      $table->string('slug');
      $table->string('thumbnail');
      $table->string('gambar_dalam');
      $table->longText('isi_berita');
      $table->foreignId('user_id');
      $table->timestamps();
      $table->timestamp('deleted_at')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('beritas');
  }
};
