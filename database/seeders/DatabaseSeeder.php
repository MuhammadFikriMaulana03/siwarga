<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        \App\Models\JenisSurat::firstOrCreate([
    'nama' => 'Surat Pengantar',
], [
    'deskripsi' => 'Surat pengantar dari RT/RW untuk keperluan administrasi.',
    'is_active' => true,
]);

\App\Models\JenisSurat::firstOrCreate([
    'nama' => 'Surat Keterangan Domisili',
], [
    'deskripsi' => 'Surat keterangan domisili warga.',
    'is_active' => true,
]);

\App\Models\JenisSurat::firstOrCreate([
    'nama' => 'Surat Keterangan Usaha',
], [
    'deskripsi' => 'Surat keterangan usaha untuk warga yang memiliki usaha.',
    'is_active' => true,
]);
    }
}
