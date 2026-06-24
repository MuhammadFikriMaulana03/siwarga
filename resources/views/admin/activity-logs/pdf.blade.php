<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Log Aktivitas</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111827;
        }

        .header {
            text-align: center;
            margin-bottom: 16px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        .header p {
            margin: 4px 0 0;
            font-size: 11px;
            color: #4b5563;
        }

        .meta {
            margin-bottom: 12px;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #eef2ff;
            color: #111827;
            font-weight: bold;
            border: 1px solid #d1d5db;
            padding: 6px;
            text-align: left;
        }

        td {
            border: 1px solid #d1d5db;
            padding: 6px;
            vertical-align: top;
        }

        .small {
            font-size: 9px;
            color: #6b7280;
        }

        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 6px;
            background: #eef2ff;
            color: #3730a3;
            font-weight: bold;
            font-size: 9px;
        }

        .footer {
            margin-top: 16px;
            font-size: 9px;
            color: #6b7280;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Log Aktivitas</h1>
        <p>Sistem Informasi RT/RW - SiWarga</p>
    </div>

    <div class="meta">
        <strong>Dicetak:</strong> {{ now()->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i:s') }} WIB<br>
        <strong>Filter Pencarian:</strong> {{ $search ?: '-' }}<br>
        <strong>Filter Modul:</strong> {{ $module ?: 'Semua Modul' }}<br>
        <strong>Total Data:</strong> {{ $logs->count() }} aktivitas
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 12%;">Waktu</th>
                <th style="width: 16%;">User</th>
                <th style="width: 12%;">Modul</th>
                <th style="width: 14%;">Aksi</th>
                <th style="width: 36%;">Keterangan</th>
                <th style="width: 10%;">IP</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
                <tr>
                    <td>
                        {{ $log->created_at?->timezone('Asia/Jakarta')->format('Y-m-d') }}<br>
                        <span class="small">
                            {{ $log->created_at?->timezone('Asia/Jakarta')->format('H:i:s') }} WIB
                        </span>
                    </td>

                    <td>
                        <strong>{{ $log->user->name ?? 'System' }}</strong><br>
                        <span class="small">{{ $log->user->email ?? '-' }}</span><br>
                        <span class="small">{{ $log->role ?? '-' }}</span>
                    </td>

                    <td>
                        <span class="badge">{{ $log->module }}</span>
                    </td>

                    <td>
                        <strong>{{ $log->action }}</strong>
                    </td>

                    <td>
                        {{ $log->description ?? '-' }}
                    </td>

                    <td>
                        {{ $log->ip_address ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">
                        Belum ada log aktivitas.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dibuat otomatis oleh SiWarga.
    </div>
</body>
</html>
