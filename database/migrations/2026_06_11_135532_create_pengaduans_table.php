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
    Schema::create('pengaduans', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('no_hp')->nullable();
        $table->string('judul');
        $table->text('isi');
        $table->string('foto')->nullable();
        $table->enum('status', ['masuk', 'diproses', 'selesai', 'ditolak'])->default('masuk');
        $table->text('tanggapan')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaduans');
    }
};