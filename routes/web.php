<?php

use App\Http\Controllers\AdminBackupController;
use App\Http\Controllers\AdminActivityLogController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminSettingController;
use App\Http\Controllers\AdminPanduanController;
use App\Http\Controllers\AdminSearchController;
use App\Http\Controllers\JenisSuratController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RtController;
use App\Http\Controllers\RtDashboardController;
use App\Http\Controllers\RtWargaController;
use App\Http\Controllers\RtIuranWargaController;
use App\Http\Controllers\RtPengaduanController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\WargaDashboardController;
use App\Http\Controllers\WargaIuranController;
use App\Http\Controllers\WargaSuratController;
use App\Http\Controllers\WargaPengaduanController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\UmkmController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\PengajuanSuratController;
use App\Http\Controllers\KartuKeluargaController;
use App\Http\Controllers\KasTransaksiController;
use App\Http\Controllers\IuranWargaController;
use App\Http\Controllers\MyProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/pengumuman/{pengumuman}', [LandingController::class, 'showPengumuman'])
    ->name('pengumuman.show');

Route::get('/kegiatan/{kegiatan}', [LandingController::class, 'showKegiatan'])
    ->name('kegiatan.show');

Route::get('/umkm/{umkm}', [LandingController::class, 'showUmkm'])
    ->name('umkm.show');

Route::get('/pengaduan', [PengaduanController::class, 'publicCreate'])
    ->name('pengaduan.create');

Route::post('/pengaduan', [PengaduanController::class, 'publicStore'])
    ->middleware('throttle:5,1')
    ->name('pengaduan.store');

Route::get('/layanan-surat', [PengajuanSuratController::class, 'publicCreate'])
    ->name('layanan-surat.create');

Route::post('/layanan-surat', [PengajuanSuratController::class, 'publicStore'])
    ->middleware('throttle:5,1')
    ->name('layanan-surat.store');

Route::get('/cek-iuran', [LandingController::class, 'cekIuran'])
    ->name('cek-iuran');

Route::post('/cek-iuran', [LandingController::class, 'cekIuranResult'])
    ->middleware('throttle:10,1')
    ->name('cek-iuran.result');

Route::get('/cek-pengaduan', [PengaduanController::class, 'cekStatus'])
    ->middleware('throttle:10,1')
    ->name('pengaduan.cek');

Route::post('/cek-pengaduan', [PengaduanController::class, 'cekStatusResult'])
    ->middleware('throttle:5,1')
    ->name('pengaduan.cek.result');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile-saya', [MyProfileController::class, 'edit'])->name('profile-saya.edit');
    Route::put('/profile-saya', [MyProfileController::class, 'update'])->name('profile-saya.update');
    Route::delete('/profile-saya/foto', [MyProfileController::class, 'removePhoto'])->name('profile-saya.photo.remove');
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->role === 'admin_rw') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'ketua_rt') {
            return redirect()->route('rt.dashboard');
        }

        return redirect()->route('warga.dashboard');
    })->name('dashboard');

    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
    ->middleware('role:admin_rw')
    ->name('admin.dashboard');

    Route::middleware(['auth','role:admin_rw'])->prefix('admin')->name('admin.')->group(function () {

        Route::get('/backups', [AdminBackupController::class, 'index'])->name('backups.index');
        Route::get('/activity-logs', [AdminActivityLogController::class, 'index'])
        ->name('activity-logs.index');
        Route::get('/search', [AdminSearchController::class, 'index'])->name('search.index');
        Route::get('/panduan', [AdminPanduanController::class, 'index'])
        ->name('panduan.index');
        Route::get('/activity-logs-export', [AdminActivityLogController::class, 'export'])
        ->name('activity-logs.export');
        Route::get('/activity-logs-pdf', [AdminActivityLogController::class, 'exportPdf'])
        ->name('activity-logs.pdf');
        Route::get('/backups/download', [AdminBackupController::class, 'download'])->name('backups.download');
        Route::post('/backups/restore', [AdminBackupController::class, 'restore'])
        ->name('backups.restore');
        Route::resource('rts', RtController::class);
        Route::resource('users', AdminUserController::class);
        Route::get('/settings', [AdminSettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
        Route::get('/wargas-import', [WargaController::class, 'importForm'])
        ->name('wargas.import.form');

        Route::post('/wargas-import', [WargaController::class, 'import'])
        ->name('wargas.import');
        Route::resource('wargas', WargaController::class);
        Route::resource('pengumuman', PengumumanController::class);
        Route::resource('kegiatans', KegiatanController::class);
        Route::resource('umkms', UmkmController::class);

        Route::resource('pengaduans', PengaduanController::class)->only([
            'index', 'edit', 'update', 'destroy'
        ]);
        Route::resource('jenis-surats', JenisSuratController::class)->except(['show']);
        Route::resource('pengajuan-surats', PengajuanSuratController::class);
        Route::get('/pengajuan-surats/{pengajuanSurat}/cetak', [PengajuanSuratController::class, 'cetak'])
        ->name('pengajuan-surats.cetak');
        Route::resource('kartu-keluargas', KartuKeluargaController::class);
        Route::get('/wargas-export', [WargaController::class, 'export'])
        ->name('wargas.export');

        Route::get('/kas-transaksis-pdf', [KasTransaksiController::class, 'exportPdf'])
        ->name('kas-transaksis.pdf');
        Route::get('/kas-transaksis-export', [KasTransaksiController::class, 'export'])
        ->name('kas-transaksis.export');
        Route::resource('kas-transaksis', KasTransaksiController::class);
        Route::get('/iuran-wargas-generate', [IuranWargaController::class, 'generateForm'])
        ->name('iuran-wargas.generate.form');

        Route::post('/iuran-wargas-generate', [IuranWargaController::class, 'generate'])
        ->name('iuran-wargas.generate');
        Route::patch('/iuran-wargas/{iuranWarga}/tandai-lunas', [IuranWargaController::class, 'tandaiLunas'])
        ->name('iuran-wargas.tandai-lunas');
        Route::get('/iuran-wargas-pdf', [IuranWargaController::class, 'exportPdf'])
        ->name('iuran-wargas.pdf');
        Route::get('/iuran-wargas-export', [IuranWargaController::class, 'export'])
        ->name('iuran-wargas.export');
        Route::resource('iuran-wargas', IuranWargaController::class);
    });

    Route::middleware(['auth', 'role:ketua_rt'])->prefix('rt')->name('rt.')->group(function () {
        Route::get('/dashboard', [RtDashboardController::class, 'index'])->name('dashboard');
        Route::get('/wargas', [RtWargaController::class, 'index'])->name('wargas.index');
        Route::get('/wargas/{warga}', [RtWargaController::class, 'show'])->name('wargas.show');
        Route::get('/iuran-wargas', [RtIuranWargaController::class, 'index'])
        ->name('iuran-wargas.index');
        Route::patch('/iuran-wargas/{iuranWarga}/tandai-lunas', [RtIuranWargaController::class, 'tandaiLunas'])
        ->name('iuran-wargas.tandai-lunas');
        Route::get('/pengaduans', [RtPengaduanController::class, 'index'])
        ->name('pengaduans.index');

        Route::get('/pengaduans/{pengaduan}', [RtPengaduanController::class, 'show'])
        ->name('pengaduans.show');

        Route::patch('/pengaduans/{pengaduan}/status', [RtPengaduanController::class, 'updateStatus'])
        ->name('pengaduans.update-status');
    });

    Route::middleware(['auth', 'role:warga'])->prefix('warga')->name('warga.')->group(function () {
    Route::get('/dashboard', [WargaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/iuran', [WargaIuranController::class, 'index'])->name('iuran.index');

    Route::get('/surat', [WargaSuratController::class, 'index'])->name('surat.index');
    Route::get('/surat/create', [WargaSuratController::class, 'create'])->name('surat.create');
    Route::post('/surat', [WargaSuratController::class, 'store'])->name('surat.store');
    Route::get('/surat/{pengajuanSurat}', [WargaSuratController::class, 'show'])->name('surat.show');

    Route::get('/pengaduan', [WargaPengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/create', [WargaPengaduanController::class, 'create'])->name('pengaduan.create');
    Route::post('/pengaduan', [WargaPengaduanController::class, 'store'])->name('pengaduan.store');
    Route::get('/pengaduan/{pengaduan}', [WargaPengaduanController::class, 'show'])->name('pengaduan.show');
});

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';
