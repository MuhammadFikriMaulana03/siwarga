<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use App\Models\KartuKeluarga;
use App\Models\KasTransaksi;
use App\Models\Kegiatan;
use App\Models\Pengaduan;
use App\Models\PengajuanSurat;
use App\Models\Pengumuman;
use App\Models\Rt;
use App\Models\Umkm;
use App\Models\User;
use App\Models\Warga;
use App\Models\IuranWarga;
use App\Models\SystemSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Pengaturan sistem
        SystemSetting::setValue('rt_rw_name', 'RW 11');
        SystemSetting::setValue('kelurahan', 'Kelurahan Pamanukan');
        SystemSetting::setValue('kecamatan', 'Kecamatan Pamanukan');
        SystemSetting::setValue('kota', 'Kabupaten Subang');
        SystemSetting::setValue('alamat_sekretariat', 'BTN Pamanukan Raya');
        SystemSetting::setValue('no_hp_rw', '081234567890');
        SystemSetting::setValue('ketua_rw', 'Madsani Arisdianto');

        // Data RT
        $rt01 = Rt::updateOrCreate(
            ['nomor_rt' => '01'],
            [
                'nama_ketua_rt' => 'Ketua RT 01',
                'no_hp' => '081111111101',
                'alamat_sekretariat' => 'Sekretariat RT 01',
                'is_active' => true,
            ]
        );

        $rt02 = Rt::updateOrCreate(
            ['nomor_rt' => '02'],
            [
                'nama_ketua_rt' => 'Ketua RT 02',
                'no_hp' => '081111111102',
                'alamat_sekretariat' => 'Sekretariat RT 02',
                'is_active' => true,
            ]
        );

        // Kartu Keluarga
        $kk01 = KartuKeluarga::updateOrCreate(
            ['no_kk' => '3213213213210001'],
            [
                'rt_id' => $rt01->id,
                'kepala_keluarga' => 'Muhammad Fikri Maulana',
                'alamat' => 'BTN Pamanukan Blok K No 18',
                'is_active' => true,
            ]
        );

        $kk02 = KartuKeluarga::updateOrCreate(
            ['no_kk' => '3213213213210002'],
            [
                'rt_id' => $rt02->id,
                'kepala_keluarga' => 'Budi Santoso',
                'alamat' => 'BTN Pamanukan Blok A No 7',
                'is_active' => true,
            ]
        );

        // Warga
        $wargaFikri = Warga::updateOrCreate(
            ['nik' => '3123123123120001'],
            [
                'rt_id' => $rt01->id,
                'kartu_keluarga_id' => $kk01->id,
                'no_kk' => $kk01->no_kk,
                'nama' => 'Muhammad Fikri Maulana',
                'tempat_lahir' => 'Subang',
                'tanggal_lahir' => '2001-03-11',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'BTN Pamanukan Blok K No 18',
                'agama' => 'Islam',
                'pekerjaan' => 'Karyawan Swasta',
                'no_hp' => '087878787878',
                'status_warga' => 'tetap',
                'is_active' => true,
            ]
        );

        $wargaBudi = Warga::updateOrCreate(
            ['nik' => '3123123123120002'],
            [
                'rt_id' => $rt02->id,
                'kartu_keluarga_id' => $kk02->id,
                'no_kk' => $kk02->no_kk,
                'nama' => 'Budi Santoso',
                'tempat_lahir' => 'Subang',
                'tanggal_lahir' => '1995-01-10',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'BTN Pamanukan Blok A No 7',
                'agama' => 'Islam',
                'pekerjaan' => 'Wiraswasta',
                'no_hp' => '081222222222',
                'status_warga' => 'tetap',
                'is_active' => true,
            ]
        );

        // User login
        User::updateOrCreate(
            ['email' => 'admin@siwarga.test'],
            [
                'name' => 'Admin RW',
                'password' => Hash::make('password'),
                'role' => 'admin_rw',
                'rt_id' => null,
                'warga_id' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'rt01@siwarga.test'],
            [
                'name' => 'Ketua RT 01',
                'password' => Hash::make('password'),
                'role' => 'ketua_rt',
                'rt_id' => $rt01->id,
                'warga_id' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'warga@siwarga.test'],
            [
                'name' => 'Warga Test',
                'password' => Hash::make('password'),
                'role' => 'warga',
                'rt_id' => null,
                'warga_id' => $wargaFikri->id,
            ]
        );

        // Jenis Surat
        $suratDomisili = JenisSurat::updateOrCreate(
            ['nama' => 'Surat Keterangan Domisili'],
            [
                'deskripsi' => 'Surat keterangan tempat tinggal warga.',
                'is_active' => true,
            ]
        );

        JenisSurat::updateOrCreate(
            ['nama' => 'Surat Pengantar'],
            [
                'deskripsi' => 'Surat pengantar keperluan administrasi.',
                'is_active' => true,
            ]
        );

        JenisSurat::updateOrCreate(
            ['nama' => 'Surat Keterangan Usaha'],
            [
                'deskripsi' => 'Surat keterangan usaha warga.',
                'is_active' => true,
            ]
        );

        // Pengumuman
        Pengumuman::updateOrCreate(
            ['judul' => 'Kerja Bakti Minggu Pagi'],
            [
                'isi' => 'Diberitahukan kepada seluruh warga untuk mengikuti kerja bakti pada hari Minggu pagi pukul 07.00 WIB.',
                'gambar' => null,
                'status' => 'published',
                'tanggal_publish' => now()->toDateString(),
            ]
        );

        // Kegiatan
        Kegiatan::updateOrCreate(
            ['judul' => 'Rapat Warga Bulanan'],
            [
                'deskripsi' => 'Rapat warga untuk membahas keamanan lingkungan, iuran, dan kegiatan bulan depan.',
                'lokasi' => 'Balai Warga',
                'tanggal' => now()->addDays(7)->toDateString(),
                'jam_mulai' => '19:30',
                'jam_selesai' => '21:00',
                'gambar' => null,
                'status' => 'published',
            ]
        );

        // UMKM
        Umkm::updateOrCreate(
            ['nama_usaha' => 'Warung Makan Bu Sari'],
            [
                'warga_id' => $wargaBudi->id,
                'pemilik' => 'Budi Santoso',
                'kategori' => 'Kuliner',
                'deskripsi' => 'Menyediakan nasi uduk, gorengan, kopi, dan makanan rumahan.',
                'no_hp' => '081222222222',
                'alamat' => 'BTN Pamanukan Blok A No 7',
                'foto' => null,
                'status' => 'aktif',
            ]
        );

        // Kas transaksi
        KasTransaksi::updateOrCreate(
            [
                'tanggal' => now()->toDateString(),
                'judul' => 'Iuran Bulanan Warga',
                'kategori' => 'Iuran Bulanan',
            ],
            [
                'tipe' => 'masuk',
                'keterangan' => 'Pemasukan iuran bulanan warga.',
                'nominal' => 100000,
            ]
        );

        KasTransaksi::updateOrCreate(
            [
                'tanggal' => now()->toDateString(),
                'judul' => 'Pembelian Alat Kebersihan',
                'kategori' => 'Kebersihan',
            ],
            [
                'tipe' => 'keluar',
                'keterangan' => 'Pembelian sapu, kantong sampah, dan alat kebersihan.',
                'nominal' => 25000,
            ]
        );

        // Iuran warga bulan ini
        IuranWarga::updateOrCreate(
            [
                'warga_id' => $wargaFikri->id,
                'bulan' => now()->month,
                'tahun' => now()->year,
            ],
            [
                'nominal' => 10000,
                'status' => 'lunas',
                'tanggal_bayar' => now()->toDateString(),
                'keterangan' => 'Iuran keamanan',
            ]
        );

        IuranWarga::updateOrCreate(
            [
                'warga_id' => $wargaBudi->id,
                'bulan' => now()->month,
                'tahun' => now()->year,
            ],
            [
                'nominal' => 10000,
                'status' => 'belum_bayar',
                'tanggal_bayar' => null,
                'keterangan' => 'Iuran keamanan',
            ]
        );

        // Pengaduan
        Pengaduan::updateOrCreate(
            ['kode_tracking' => 'PGD-' . now()->format('Ymd') . '-0001'],
            [
                'rt_id' => $rt01->id,
                'nama' => 'Muhammad Fikri Maulana',
                'no_hp' => '087878787878',
                'judul' => 'Lampu Jalan Mati',
                'isi' => 'Lampu jalan dekat blok K mati sejak kemarin malam.',
                'foto' => null,
                'status' => 'masuk',
                'tanggapan' => null,
            ]
        );

        // Pengajuan surat
        PengajuanSurat::updateOrCreate(
            ['kode_tracking' => 'SRT-' . now()->format('Ymd') . '-0001'],
            [
                'jenis_surat_id' => $suratDomisili->id,
                'warga_id' => $wargaFikri->id,
                'nama_pemohon' => $wargaFikri->nama,
                'nik' => $wargaFikri->nik,
                'no_hp' => $wargaFikri->no_hp,
                'alamat' => $wargaFikri->alamat,
                'keperluan' => 'Keperluan administrasi pekerjaan.',
                'status' => 'selesai',
                'catatan_admin' => 'Surat sudah selesai diproses.',
            ]
        );
    }
}