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
    Schema::table('wargas', function (Blueprint $table) {
        $table->foreignId('kartu_keluarga_id')
            ->nullable()
            ->after('rt_id')
            ->constrained('kartu_keluargas')
            ->nullOnDelete();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('wargas', function (Blueprint $table) {
        $table->dropConstrainedForeignId('kartu_keluarga_id');
    });
}
};
