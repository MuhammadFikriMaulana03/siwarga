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
    Schema::create('iuran_wargas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('warga_id')->constrained('wargas')->cascadeOnDelete();
        $table->unsignedTinyInteger('bulan');
        $table->unsignedSmallInteger('tahun');
        $table->decimal('nominal', 15, 2);
        $table->enum('status', ['belum_bayar', 'lunas'])->default('belum_bayar');
        $table->date('tanggal_bayar')->nullable();
        $table->text('keterangan')->nullable();
        $table->timestamps();

        $table->unique(['warga_id', 'bulan', 'tahun']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iuran_wargas');
    }
};