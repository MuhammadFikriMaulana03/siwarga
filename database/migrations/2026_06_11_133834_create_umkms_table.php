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
    Schema::create('umkms', function (Blueprint $table) {
        $table->id();
        $table->foreignId('warga_id')->nullable()->constrained('wargas')->nullOnDelete();
        $table->string('nama_usaha');
        $table->string('pemilik')->nullable();
        $table->string('kategori')->nullable();
        $table->text('deskripsi')->nullable();
        $table->string('no_hp')->nullable();
        $table->text('alamat')->nullable();
        $table->string('foto')->nullable();
        $table->enum('status', ['draft', 'publish'])->default('publish');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umkms');
    }
};