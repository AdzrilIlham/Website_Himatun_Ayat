<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Data Anak Asuh - Yayasan Himatun Ayat Bandung</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333333;
            font-size: 10px;
            line-height: 1.4;
            margin: 15px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2D6A4F;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }
        .header h1 {
            color: #2D6A4F;
            font-size: 16px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 3px 0 0;
            color: #555555;
            font-size: 9px;
        }
        .title-block {
            margin-bottom: 12px;
        }
        .title-block h2 {
            font-size: 13px;
            color: #1b4332;
            margin: 0 0 4px;
        }
        .title-block p {
            color: #666;
            margin: 0;
            font-size: 9px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data-table th {
            background-color: #2D6A4F;
            color: #ffffff;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 6px 8px;
            text-align: left;
            border: 1px solid #1b4332;
        }
        table.data-table td {
            padding: 5px 8px;
            border: 1px solid #e2e8f0;
            font-size: 9px;
            vertical-align: middle;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 9999px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-aktif {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-alumni {
            background-color: #e2e8f0;
            color: #334155;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 8px;
            color: #888888;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Yayasan Himmatun Ayat Bandung</h1>
        <p>Jl. Cibiru Indah 7 RT/RW 04/14 Des. Cibiru Wetan Kec. Cileunyi Kab. Bandung</p>
    </div>

    <div class="title-block">
        <h2>Laporan Data Anak Asuh</h2>
        <p>Waktu Unduh: {{ now()->translatedFormat('d F Y H:i') }} WIB | Total Data: {{ $anakAsuh->count() }} Santri</p>
        @if($search || ($status && $status !== 'all' && $status !== 'Semua Status'))
            <p>Filter: {{ $search ? "Pencarian: '{$search}'" : '' }} {{ $status && $status !== 'all' && $status !== 'Semua Status' ? "| Status: {$status}" : '' }}</p>
        @endif
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 25%;">Nama Lengkap</th>
                <th style="width: 12%;">Nama Panggilan</th>
                <th style="width: 8%;">L/P</th>
                <th style="width: 10%;">Usia</th>
                <th style="width: 14%;">Pendidikan</th>
                <th style="width: 12%;">Status</th>
                <th style="width: 15%;">Tgl Bergabung</th>
            </tr>
        </thead>
        <tbody>
            @forelse($anakAsuh as $index => $anak)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>{{ $anak->nama_lengkap }}</strong></td>
                    <td>{{ $anak->nama_panggilan ?: '-' }}</td>
                    <td style="text-align: center;">{{ $anak->jenis_kelamin }}</td>
                    <td>{{ $anak->usia_formatted }}</td>
                    <td>{{ $anak->pendidikan_terakhir ?: '-' }}</td>
                    <td>
                        <span class="badge {{ $anak->status_asuhan === 'Aktif' ? 'badge-aktif' : 'badge-alumni' }}">
                            {{ $anak->status_asuhan }}
                        </span>
                    </td>
                    <td>{{ $anak->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 15px; color: #888;">
                        Tidak ada data anak asuh.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak otomatis oleh Sistem Informasi Administrasi Yayasan Himatun Ayat Bandung
    </div>
</body>
</html>
