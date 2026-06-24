<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kas RT/RW</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111827;
            line-height: 1.5;
        }

        .kop {
            text-align: center;
            border-bottom: 3px solid #111827;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .kop h1 {
            font-size: 17px;
            margin: 0;
            text-transform: uppercase;
        }

        .kop h2 {
            font-size: 15px;
            margin: 4px 0;
            text-transform: uppercase;
        }

        .kop p {
            margin: 2px 0;
            font-size: 10px;
        }

        .title {
            text-align: center;
            margin-bottom: 18px;
        }

        .title h3 {
            font-size: 15px;
            text-decoration: underline;
            margin: 0;
            text-transform: uppercase;
        }

        .summary {
            width: 100%;
            margin-bottom: 18px;
        }

        .summary td {
            border: 1px solid #d1d5db;
            padding: 8px;
            font-weight: bold;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
        }

        table.data th {
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 7px;
            text-align: left;
        }

        table.data td {
            border: 1px solid #d1d5db;
            padding: 7px;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .masuk {
            color: #047857;
            font-weight: bold;
        }

        .keluar {
            color: #b91c1c;
            font-weight: bold;
        }

        .signature {
            margin-top: 40px;
            width: 100%;
        }

        .signature td {
            text-align: center;
            width: 50%;
        }

        .name {
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    @php
        $namaBulan = $bulan
            ? \Carbon\Carbon::create()->month((int) $bulan)->translatedFormat('F')
            : null;

        $periode = $namaBulan || $tahun
            ? trim(($namaBulan ?? 'Semua Bulan') . ' ' . ($tahun ?? ''))
            : 'Semua Periode';
    @endphp
    <div class="kop">
        <h1>Rukun Warga {{ config('siwarga.rt_rw_name') }}</h1>
        <h2>{{ config('siwarga.kelurahan') }}</h2>
        <p>{{ config('siwarga.kecamatan') }} - {{ config('siwarga.kota') }}</p>
        <p>{{ config('siwarga.alamat_sekretariat') }}</p>
        <p>Telp/WA: {{ config('siwarga.no_hp_rw') }}</p>
    </div>

    <div class="title">
        <h3>Laporan Kas RT/RW</h3>
        <p>Periode: {{ $periode }}</p>
        <p>Dicetak pada {{ now()->translatedFormat('d F Y H:i') }}</p>
    </div>

    <table class="summary">
        <tr>
            <td>Total Kas Masuk</td>
            <td class="text-right">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Total Kas Keluar</td>
            <td class="text-right">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Saldo Kas</td>
            <td class="text-right">Rp {{ number_format($saldo, 0, ',', '.') }}</td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 75px;">Tanggal</th>
                <th>Judul</th>
                <th style="width: 85px;">Kategori</th>
                <th style="width: 60px;">Tipe</th>
                <th style="width: 90px;" class="text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kasTransaksis as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>
                        <strong>{{ $item->judul }}</strong><br>
                        <span>{{ $item->keterangan ?? '-' }}</span>
                    </td>
                    <td>{{ $item->kategori ?? '-' }}</td>
                    <td class="{{ $item->tipe === 'masuk' ? 'masuk' : 'keluar' }}">
                        {{ ucfirst($item->tipe) }}
                    </td>
                    <td class="text-right">
                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Belum ada transaksi kas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="signature">
        <tr>
            <td></td>
            <td>
                {{ now()->translatedFormat('d F Y') }}<br>
                Ketua RT/RW
                <div class="name">{{ config('siwarga.ketua_rw') }}</div>
            </td>
        </tr>
    </table>
</body>
</html>
