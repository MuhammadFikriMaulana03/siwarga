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
    Schema::create('wargas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('rt_id')->constrained('rts')->cascadeOnDelete();
        $table->string('nik', 20)->unique();
        $table->string('no_kk', 20)->nullable();
        $table->string('nama');
        $table->string('tempat_lahir')->nullable();
        $table->date('tanggal_lahir')->nullable();
        $table->enum('jenis_kelamin', ['L', 'P']);
        $table->text('alamat')->nullable();
        $table->string('agama')->nullable();
        $table->string('pekerjaan')->nullable();
        $table->string('no_hp')->nullable();
        $table->enum('status_warga', ['tetap', 'kontrak', 'pendatang'])->default('tetap');
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wargas');
    }
};