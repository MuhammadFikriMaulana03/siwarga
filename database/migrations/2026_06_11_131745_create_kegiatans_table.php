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
    Schema::create('kegiatans', function (Blueprint $table) {
        $table->id();
        $table->string('judul');
        $table->text('deskripsi');
        $table->string('lokasi')->nullable();
        $table->date('tanggal');
        $table->time('jam_mulai')->nullable();
        $table->time('jam_selesai')->nullable();
        $table->string('gambar')->nullable();
        $table->enum('status', ['draft', 'publish'])->default('publish');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};