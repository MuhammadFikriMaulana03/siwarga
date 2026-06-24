<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat {{ $pengajuanSurat->jenisSurat->nama ?? '' }}</title>

    <style>
        @page {
            margin: 35px 45px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111827;
            line-height: 1.5;
        }

        .kop {
            text-align: center;
            border-bottom: 3px solid #111827;
            padding-bottom: 8px;
            margin-bottom: 16px;
        }

        .kop-title {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.35;
        }

        .kop-subtitle {
            margin: 2px 0 0;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.35;
        }

        .kop-address {
            margin: 3px 0 0;
            font-size: 10.5px;
            line-height: 1.35;
        }

        .title {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 18px;
        }

        .title h3 {
            display: inline-block;
            margin: 0;
            padding-bottom: 1px;
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px solid #111827;
            letter-spacing: 0.5px;
        }

        .title p {
            margin: 3px 0 0;
            font-size: 11px;
        }

        .intro {
            margin: 0 0 8px;
            text-align: justify;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0 14px;
        }

        .data-table td {
            vertical-align: top;
            padding: 3px 0;
        }

        .number {
            width: 22px;
        }

        .label {
            width: 135px;
        }

        .colon {
            width: 12px;
        }

        .value {
            border-bottom: 1px dotted #111827;
            min-height: 18px;
        }

        .content {
            text-align: justify;
        }

        .content p {
            margin: 8px 0;
        }

        .need-box {
            margin: 4px 0 10px 0;
            padding-bottom: 2px;
            border-bottom: 1px dotted #111827;
            font-weight: bold;
        }

        .signature-wrapper {
            margin-top: 24px;
            page-break-inside: avoid;
        }

        .signature-date {
            width: 100%;
            text-align: left;
            margin-bottom: 22px;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        .signature-table td {
            width: 33.33%;
            vertical-align: top;
            padding: 0 10px;
        }

        .signature-position {
            margin: 0;
            font-size: 11.5px;
        }

        .signature-space {
            height: 62px;
        }

        .signature-name {
            margin: 0;
            font-weight: bold;
            text-decoration: none;
        }

        .signature-region {
            margin: 3px 0 0;
            font-size: 11px;
        }
    </style>
</head>

<body>
    @php
        $settings = $settings ?? \App\Models\SystemSetting::allSettings();

        $rtRwName = $settings['rt_rw_name'] ?? 'RW 11';
        $kelurahan = $settings['kelurahan'] ?? '-';
        $kecamatan = $settings['kecamatan'] ?? '-';
        $kota = $settings['kota'] ?? '-';
        $alamatSekretariat = $settings['alamat_sekretariat'] ?? '-';
        $noHpRw = $settings['no_hp_rw'] ?? null;
        $ketuaRw = $settings['ketua_rw'] ?? '................................';
        $dataWarga = $dataWarga
            ?? $pengajuanSurat->warga
            ?? \App\Models\Warga::where('nik', $pengajuanSurat->nik)->first();

        $jenisKelaminRaw = $dataWarga->jenis_kelamin ?? null;

        $jenisKelamin = match ($jenisKelaminRaw) {
            'L', 'l', 'Laki-laki', 'laki-laki', 'Laki-Laki' => 'Laki-laki',
            'P', 'p', 'Perempuan', 'perempuan' => 'Perempuan',
            default => $jenisKelaminRaw ?? '-',
        };

        $agama = $dataWarga->agama ?? '-';

        $tempatTanggalLahir = '-';

        if ($dataWarga) {
            $tempatLahir = $dataWarga->tempat_lahir ?? '-';

            $tanggalLahir = $dataWarga->tanggal_lahir
                ? \Carbon\Carbon::parse($dataWarga->tanggal_lahir)->translatedFormat('d F Y')
                : '-';

            $tempatTanggalLahir = $tempatLahir . ', ' . $tanggalLahir;
        }

        $namaKetuaRt01 = $namaKetuaRt01 ?? ($rt01->nama_ketua_rt ?? '................................');
        $namaKetuaRt02 = $namaKetuaRt02 ?? ($rt02->nama_ketua_rt ?? '................................');

        $bulanRomawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ];

        $namaSurat = strtolower($pengajuanSurat->jenisSurat->nama ?? '');

        if (str_contains($namaSurat, 'domisili')) {
            $kodeSurat = 'SKD';
        } elseif (str_contains($namaSurat, 'usaha')) {
            $kodeSurat = 'SKU';
        } elseif (str_contains($namaSurat, 'pengantar')) {
            $kodeSurat = 'SP';
        } else {
            $kodeSurat = 'SK';
        }

        $nomorSurat = str_pad($pengajuanSurat->id, 3, '0', STR_PAD_LEFT)
            . '/' . $kodeSurat
            . '/RT-RW/'
            . $bulanRomawi[now()->month]
            . '/' . now()->year;
    @endphp

    {{-- Kop Surat --}}
    <div class="kop">
        <p class="kop-title">
            Pengurus {{ $rtRwName }}
        </p>

        <p class="kop-subtitle">
            {{ $kelurahan }}, {{ $kecamatan }}
        </p>

        <p class="kop-subtitle">
            {{ $kota }}
        </p>

        <p class="kop-address">
            Sekretariat: {{ $alamatSekretariat }}
            @if (!empty($noHpRw))
                · Telp/WA: {{ $noHpRw }}
            @endif
        </p>
    </div>

    {{-- Judul Surat --}}
    <div class="title">
        <h3>{{ $pengajuanSurat->jenisSurat->nama ?? 'Surat Pengantar' }}</h3>
        <p>Nomor: {{ $nomorSurat }}</p>
    </div>

    <p class="intro">
        Yang bertanda tangan di bawah ini, pengurus RT/RW menerangkan bahwa:
    </p>

    {{-- Data Pemohon --}}
    <table class="data-table">
        <tr>
            <td class="number">1.</td>
            <td class="label">Nama</td>
            <td class="colon">:</td>
            <td class="value">{{ $pengajuanSurat->nama_pemohon }}</td>
        </tr>

        <tr>
            <td class="number">2.</td>
            <td class="label">NIK</td>
            <td class="colon">:</td>
            <td class="value">{{ $pengajuanSurat->nik }}</td>
        </tr>

        <tr>
            <td class="number">3.</td>
            <td class="label">Jenis Kelamin</td>
            <td class="colon">:</td>
            <td class="value">{{ $jenisKelamin }}</td>
        </tr>

        <tr>
            <td class="number">4.</td>
            <td class="label">Tempat/Tanggal Lahir</td>
            <td class="colon">:</td>
            <td class="value">{{ $tempatTanggalLahir }}</td>
        </tr>

        <tr>
            <td class="number">5.</td>
            <td class="label">Agama</td>
            <td class="colon">:</td>
            <td class="value">{{ $agama }}</td>
        </tr>

        <tr>
            <td class="number">6.</td>
            <td class="label">No HP</td>
            <td class="colon">:</td>
            <td class="value">{{ $pengajuanSurat->no_hp ?? '-' }}</td>
        </tr>

        <tr>
            <td class="number">7.</td>
            <td class="label">Alamat</td>
            <td class="colon">:</td>
            <td class="value">{{ $pengajuanSurat->alamat ?? '-' }}</td>
        </tr>

        <tr>
            <td class="number">8.</td>
            <td class="label">Keperluan</td>
            <td class="colon">:</td>
            <td class="value">{{ $pengajuanSurat->keperluan }}</td>
        </tr>
    </table>

    {{-- Isi Surat --}}
    <div class="content">
        @if ($kodeSurat === 'SKD')
            <p>
                Benar nama tersebut di atas merupakan warga yang berdomisili di lingkungan
                {{ $rtRwName }}, {{ $kelurahan }}, {{ $kecamatan }}, {{ $kota }}.
            </p>

            <p>
                Surat keterangan domisili ini dibuat dan dipergunakan untuk keperluan:
            </p>

            <div class="need-box">
                {{ $pengajuanSurat->keperluan }}
            </div>

            <p>
                Demikian surat keterangan ini dibuat dengan sebenar-benarnya agar dapat
                dipergunakan sebagaimana mestinya.
            </p>
        @elseif ($kodeSurat === 'SKU')
            <p>
                Benar nama tersebut di atas memiliki kegiatan usaha di lingkungan
                {{ $rtRwName }}, {{ $kelurahan }}, {{ $kecamatan }}, {{ $kota }}.
            </p>

            <p>
                Surat keterangan usaha ini dibuat dan dipergunakan untuk keperluan:
            </p>

            <div class="need-box">
                {{ $pengajuanSurat->keperluan }}
            </div>

            <p>
                Demikian surat keterangan ini dibuat dengan sebenar-benarnya agar dapat
                dipergunakan sebagaimana mestinya.
            </p>
        @else
            <p>
                Benar yang bersangkutan adalah warga/penduduk di lingkungan
                {{ $rtRwName }}, {{ $kelurahan }}, {{ $kecamatan }}, {{ $kota }}.
            </p>

            <p>
                Surat ini dibuat sebagai pengantar untuk keperluan:
            </p>

            <div class="need-box">
                {{ $pengajuanSurat->keperluan }}
            </div>

            <p>
                Demikian surat pengantar ini dibuat dengan sebenar-benarnya agar dapat
                dipergunakan sebagaimana mestinya.
            </p>
        @endif
    </div>

    {{-- Tanda Tangan --}}
    <div class="signature-wrapper">
        <div class="signature-date">
            {{ $kota }}, {{ now()->translatedFormat('d F Y') }}
        </div>

        <table class="signature-table">
            <tr>
                <td>
                    <p class="signature-position">Ketua RT 01</p>

                    <div class="signature-space"></div>

                    <p class="signature-name">
                        {{ $namaKetuaRt01 }}
                    </p>

                    <p class="signature-region">
                        RT 01
                    </p>
                </td>

                <td>
                    <p class="signature-position">Ketua RT 02</p>

                    <div class="signature-space"></div>

                    <p class="signature-name">
                        {{ $namaKetuaRt02 }}
                    </p>

                    <p class="signature-region">
                        RT 02
                    </p>
                </td>

                <td>
                    <p class="signature-position">Ketua {{ $rtRwName }}</p>

                    <div class="signature-space"></div>

                    <p class="signature-name">
                        {{ $ketuaRw }}
                    </p>

                    <p class="signature-region">
                        {{ $rtRwName }}
                    </p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
