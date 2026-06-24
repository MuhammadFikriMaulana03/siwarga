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
    Schema::create('kas_transaksis', function (Blueprint $table) {
        $table->id();
        $table->date('tanggal');
        $table->enum('tipe', ['masuk', 'keluar']);
        $table->string('kategori')->nullable();
        $table->string('judul');
        $table->text('keterangan')->nullable();
        $table->decimal('nominal', 15, 2);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas_transaksis');
    }
};