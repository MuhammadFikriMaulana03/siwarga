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
    Schema::create('inventaris', function (Blueprint $table) {

        $table->id();

        $table->string('kode_barang')->unique();

        $table->string('nama_barang');

        $table->string('kategori')->nullable();

        $table->integer('jumlah')->default(1);

        $table->string('satuan')->default('Unit');

        $table->string('lokasi')->nullable();

        $table->enum('kondisi', [
            'baik',
            'rusak_ringan',
            'rusak_berat'
        ])->default('baik');

        $table->date('tanggal_beli')->nullable();

        $table->decimal('harga_beli',15,2)->nullable();

        $table->string('foto')->nullable();

        $table->text('keterangan')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris');
    }
};