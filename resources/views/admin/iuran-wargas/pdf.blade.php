<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Iuran Warga</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111827;
            line-height: 1.5;
        }

        .kop {
            text-align: center;
            border-bottom: 3px solid #111827;
            padding-bottom: 12px;
            margin-bottom: 18px;
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
            margin-bottom: 16px;
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
            padding: 7px;
            font-weight: bold;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
        }

        table.data th {
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 6px;
            text-align: left;
        }

        table.data td {
            border: 1px solid #d1d5db;
            padding: 6px;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .lunas {
            color: #047857;
            font-weight: bold;
        }

        .belum {
            color: #b91c1c;
            font-weight: bold;
        }

        .signature {
            margin-top: 35px;
            width: 100%;
        }

        .signature td {
            text-align: center;
            width: 50%;
        }

        .name {
            margin-top: 55px;
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
        <h3>Laporan Iuran Warga</h3>
        <p>Periode: {{ $periode }}</p>
        <p>Dicetak pada {{ now()->translatedFormat('d F Y H:i') }}</p>
    </div>

    <table class="summary">
        <tr>
            <td>Total Tagihan</td>
            <td class="text-right">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Sudah Lunas</td>
            <td class="text-right">Rp {{ number_format($totalLunas, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Belum Bayar</td>
            <td class="text-right">Rp {{ number_format($totalBelumBayar, 0, ',', '.') }}</td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th>Nama Warga</th>
                <th style="width: 55px;">RT</th>
                <th style="width: 85px;">Periode</th>
                <th style="width: 80px;" class="text-right">Nominal</th>
                <th style="width: 75px;">Status</th>
                <th style="width: 80px;">Tanggal Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($iuranWargas as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $item->warga->nama ?? '-' }}</strong><br>
                        NIK {{ $item->warga->nik ?? '-' }}<br>
                        KK {{ $item->warga->kartuKeluarga->no_kk ?? $item->warga->no_kk ?? '-' }}
                    </td>
                    <td>RT {{ $item->warga->rt->nomor_rt ?? '-' }}</td>
                    <td>
                        {{ \Carbon\Carbon::create()->month((int) $item->bulan)->translatedFormat('F') }}
                        {{ $item->tahun }}
                    </td>
                    <td class="text-right">
                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                    </td>
                    <td class="{{ $item->status === 'lunas' ? 'lunas' : 'belum' }}">
                        {{ $item->status === 'lunas' ? 'Lunas' : 'Belum Bayar' }}
                    </td>
                    <td>{{ $item->tanggal_bayar ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Belum ada data iuran warga.</td>
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
