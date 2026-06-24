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
    Schema::create('kartu_keluargas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('rt_id')->constrained('rts')->cascadeOnDelete();
        $table->string('no_kk', 20)->unique();
        $table->string('kepala_keluarga');
        $table->text('alamat')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kartu_keluargas');
    }
};
