<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_surats', function (Blueprint $table) {
            $table->string('kode_tracking')->nullable()->unique()->after('id');
        });

        $pengajuanSurats = DB::table('pengajuan_surats')
            ->select('id', 'created_at')
            ->orderBy('id')
            ->get();

        foreach ($pengajuanSurats as $surat) {
            $tanggal = $surat->created_at
                ? date('Ymd', strtotime($surat->created_at))
                : now()->format('Ymd');

            DB::table('pengajuan_surats')
                ->where('id', $surat->id)
                ->update([
                    'kode_tracking' => 'SRT-' . $tanggal . '-' . str_pad($surat->id, 4, '0', STR_PAD_LEFT),
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('pengajuan_surats', function (Blueprint $table) {
            $table->dropUnique(['kode_tracking']);
            $table->dropColumn('kode_tracking');
        });
    }
};
