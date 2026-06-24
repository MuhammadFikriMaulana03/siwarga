<x-layouts.admin header="Panduan Admin">
    <div class="p-4 sm:p-6">
        <div class="mb-6">
            <p class="text-sm font-bold text-indigo-600">Dokumentasi</p>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">
                Panduan Penggunaan SiWarga
            </h1>
            <p class="text-slate-500 text-sm mt-2">
                Panduan singkat untuk mengelola sistem informasi RT/RW melalui admin panel.
            </p>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                    <h2 class="text-xl font-extrabold text-slate-900 mb-4">
                        Alur Utama Penggunaan
                    </h2>

                    <div class="space-y-4">
                        <div class="flex gap-4">
                            <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-extrabold shrink-0">
                                1
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900">Lengkapi Pengaturan Sistem</h3>
                                <p class="text-sm text-slate-500 mt-1">
                                    Masuk ke menu Pengaturan untuk mengisi nama RW, kelurahan, kecamatan,
                                    alamat sekretariat, nomor HP, dan nama ketua RW.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-extrabold shrink-0">
                                2
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900">Input Data RT dan Warga</h3>
                                <p class="text-sm text-slate-500 mt-1">
                                    Tambahkan data RT, kartu keluarga, dan warga. Data ini akan menjadi dasar
                                    untuk akun warga, akun ketua RT, iuran, surat, dan pengaduan.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-extrabold shrink-0">
                                3
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900">Buat Akun User</h3>
                                <p class="text-sm text-slate-500 mt-1">
                                    Gunakan menu Kelola User untuk membuat akun Admin RW, Ketua RT, dan Warga.
                                    Akun warga wajib dihubungkan ke data warga, sedangkan ketua RT wajib dihubungkan ke data RT.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-extrabold shrink-0">
                                4
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900">Kelola Layanan Warga</h3>
                                <p class="text-sm text-slate-500 mt-1">
                                    Admin dapat mengelola pengajuan surat, pengaduan, iuran warga,
                                    kas RT/RW, pengumuman, kegiatan, dan UMKM warga.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-extrabold shrink-0">
                                5
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900">Backup Data Secara Berkala</h3>
                                <p class="text-sm text-slate-500 mt-1">
                                    Gunakan menu Backup Data untuk mengunduh cadangan database sebelum melakukan perubahan besar.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100">
                        <h2 class="text-xl font-extrabold text-slate-900">
                            Panduan Menu Admin
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Ringkasan fungsi setiap menu di admin panel.
                        </p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-slate-50 text-left border-b">
                                    <th class="p-4">Menu</th>
                                    <th class="p-4">Fungsi</th>
                                    <th class="p-4">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b">
                                    <td class="p-4 font-bold">Dashboard</td>
                                    <td class="p-4">Melihat ringkasan warga, kas, iuran, surat, dan pengaduan.</td>
                                    <td class="p-4 text-slate-500">Bisa difilter bulan dan tahun.</td>
                                </tr>

                                <tr class="border-b">
                                    <td class="p-4 font-bold">Kelola User</td>
                                    <td class="p-4">Membuat akun admin, ketua RT, dan warga.</td>
                                    <td class="p-4 text-slate-500">Register publik sudah dinonaktifkan.</td>
                                </tr>

                                <tr class="border-b">
                                    <td class="p-4 font-bold">Data Warga</td>
                                    <td class="p-4">Mengelola data warga, import, export, dan filter data.</td>
                                    <td class="p-4 text-slate-500">NIK dan No KK harus rapi agar akun warga mudah dihubungkan.</td>
                                </tr>

                                <tr class="border-b">
                                    <td class="p-4 font-bold">Layanan Surat</td>
                                    <td class="p-4">Memproses pengajuan surat warga dan mencetak PDF surat.</td>
                                    <td class="p-4 text-slate-500">Surat hanya bisa dicetak saat status selesai.</td>
                                </tr>

                                <tr class="border-b">
                                    <td class="p-4 font-bold">Iuran Warga</td>
                                    <td class="p-4">Generate tagihan, tandai lunas, dan sinkron otomatis ke kas.</td>
                                    <td class="p-4 text-slate-500">Iuran lunas akan masuk sebagai kas masuk.</td>
                                </tr>

                                <tr class="border-b">
                                    <td class="p-4 font-bold">Pengaduan</td>
                                    <td class="p-4">Melihat, menanggapi, dan mengubah status pengaduan warga.</td>
                                    <td class="p-4 text-slate-500">Warga bisa cek status dari panel warga.</td>
                                </tr>

                                <tr class="border-b">
                                    <td class="p-4 font-bold">Backup Data</td>
                                    <td class="p-4">Download dan restore database SQL.</td>
                                    <td class="p-4 text-slate-500">Gunakan dengan hati-hati saat restore.</td>
                                </tr>

                                <tr>
                                    <td class="p-4 font-bold">Log Aktivitas</td>
                                    <td class="p-4">Melihat riwayat aktivitas penting di sistem.</td>
                                    <td class="p-4 text-slate-500">Bisa export CSV dan PDF.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                    <h2 class="text-lg font-extrabold text-slate-900 mb-4">
                        Role Pengguna
                    </h2>

                    <div class="space-y-4">
                        <div class="rounded-2xl bg-indigo-50 p-4">
                            <p class="font-extrabold text-indigo-700">Admin RW</p>
                            <p class="text-sm text-indigo-700 mt-1">
                                Mengelola seluruh data dan fitur sistem.
                            </p>
                        </div>

                        <div class="rounded-2xl bg-emerald-50 p-4">
                            <p class="font-extrabold text-emerald-700">Ketua RT</p>
                            <p class="text-sm text-emerald-700 mt-1">
                                Melihat data warga RT, iuran RT, dan pengaduan warga RT.
                            </p>
                        </div>

                        <div class="rounded-2xl bg-amber-50 p-4">
                            <p class="font-extrabold text-amber-700">Warga</p>
                            <p class="text-sm text-amber-700 mt-1">
                                Melihat data pribadi, iuran, surat, dan pengaduan miliknya.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-red-50 rounded-3xl border border-red-100 p-6">
                    <h2 class="text-lg font-extrabold text-red-700 mb-3">
                        Catatan Keamanan
                    </h2>
                    <ul class="text-sm text-red-700 space-y-2 list-disc list-inside">
                        <li>Jangan bagikan akun admin kepada orang lain.</li>
                        <li>Lakukan backup sebelum import atau restore data.</li>
                        <li>Pastikan akun warga terhubung ke data warga yang benar.</li>
                        <li>Hapus akun yang sudah tidak digunakan.</li>
                    </ul>
                </div>

                <div class="bg-slate-950 rounded-3xl p-6 text-white">
                    <h2 class="text-lg font-extrabold mb-3">
                        Tips Demo
                    </h2>
                    <p class="text-sm text-slate-300 leading-relaxed">
                        Saat demo, mulai dari dashboard, lalu tunjukkan alur data warga,
                        generate iuran, pengajuan surat, pengaduan, dan terakhir backup data.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
