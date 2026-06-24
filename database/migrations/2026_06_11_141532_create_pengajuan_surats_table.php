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
    Schema::create('pengajuan_surats', function (Blueprint $table) {
        $table->id();
        $table->foreignId('jenis_surat_id')->constrained('jenis_surats')->cascadeOnDelete();
        $table->foreignId('warga_id')->nullable()->constrained('wargas')->nullOnDelete();

        $table->string('nama_pemohon');
        $table->string('nik', 20);
        $table->string('no_hp')->nullable();
        $table->text('alamat')->nullable();
        $table->text('keperluan');

        $table->enum('status', ['menunggu', 'diproses', 'selesai', 'ditolak'])->default('menunggu');
        $table->text('catatan_admin')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surats');
    }
};