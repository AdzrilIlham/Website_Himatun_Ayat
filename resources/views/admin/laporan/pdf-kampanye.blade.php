<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Data Kampanye - Yayasan Himmatun Ayat Bandung</title>
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
        .summary-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .summary-box {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 8px 12px;
            border-radius: 4px;
            text-align: center;
        }
        .summary-title {
            font-size: 9px;
            color: #166534;
            text-transform: uppercase;
            font-weight: bold;
        }
        .summary-val {
            font-size: 13px;
            color: #14532d;
            font-weight: bold;
            margin-top: 2px;
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
        .badge-selesai {
            background-color: #e2e8f0;
            color: #334155;
        }
        .nominal {
            text-align: right;
            font-weight: bold;
            color: #1b4332;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 9px;
        }
        .signature-box {
            display: inline-block;
            text-align: center;
            width: 180px;
        }
        .signature-line {
            margin-top: 45px;
            border-top: 1px solid #333;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Yayasan Himmatun Ayat Bandung</h1>
        <p>Jl. Cibiru Indah 7 RT/RW 04/14 Des. Cibiru Wetan Kec. Cileunyi Kab. Bandung</p>
        <p>Telepon / WhatsApp: +62 812-2334-455 &bull; Email: admin@himmatunayat.org</p>
    </div>

    <div class="title-block">
        <h2>LAPORAN DATA KAMPANYE DONASI</h2>
        <p>Periode: {{ $bulan }} {{ $tahun }} &bull; Kategori: {{ $kategori }} &bull; Waktu Cetak: {{ now()->translatedFormat('d F Y H:i') }} WIB</p>
    </div>

    <table class="summary-table">
        <tr>
            <td width="33.3%" style="padding-right: 4px;">
                <div class="summary-box">
                    <div class="summary-title">Total Kampanye</div>
                    <div class="summary-val">{{ $stats['total'] ?? $kampanye->count() }} Program</div>
                </div>
            </td>
            <td width="33.3%" style="padding: 0 4px;">
                <div class="summary-box">
                    <div class="summary-title">Dana Terkumpul</div>
                    <div class="summary-val">Rp{{ number_format($stats['terkumpul_raw'] ?? $kampanye->sum('dana_terkumpul'), 0, ',', '.') }}</div>
                </div>
            </td>
            <td width="33.3%" style="padding-left: 4px;">
                <div class="summary-box">
                    <div class="summary-title">Target Donasi</div>
                    <div class="summary-val">Rp{{ number_format($stats['target_raw'] ?? $kampanye->sum('target_dana'), 0, ',', '.') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 32%;">Judul Kampanye</th>
                <th style="width: 18%;">Kategori</th>
                <th style="width: 16%; text-align: right;">Target Dana</th>
                <th style="width: 16%; text-align: right;">Dana Terkumpul</th>
                <th style="width: 8%; text-align: center;">Tercapai</th>
                <th style="width: 6%; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kampanye as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>{{ $item->judul }}</strong></td>
                    <td>{{ $item->kategori }}</td>
                    <td class="nominal">Rp{{ number_format($item->target_dana, 0, ',', '.') }}</td>
                    <td class="nominal">Rp{{ number_format($item->dana_terkumpul, 0, ',', '.') }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $item->persentase_tercapai }}%</td>
                    <td style="text-align: center;">
                        <span class="badge {{ $item->status === 'aktif' ? 'badge-aktif' : 'badge-selesai' }}">
                            {{ ucfirst($item->status ?? 'Aktif') }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 15px; color: #888;">
                        Tidak ada data kampanye yang sesuai dengan filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($kampanye->count() > 0)
            <tfoot>
                <tr style="background-color: #f1f5f9; font-weight: bold;">
                    <td colspan="3" style="text-align: right; padding: 6px 8px;">TOTAL KESELURUHAN:</td>
                    <td class="nominal">Rp{{ number_format($kampanye->sum('target_dana'), 0, ',', '.') }}</td>
                    <td class="nominal">Rp{{ number_format($kampanye->sum('dana_terkumpul'), 0, ',', '.') }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        @endif
    </table>

    <div class="footer">
        <div class="signature-box">
            <p>Bandung, {{ now()->translatedFormat('d F Y') }}</p>
            <p style="margin-top: -6px;">Pengurus Yayasan</p>
            <div class="signature-line">
                Admin Yayasan
            </div>
        </div>
    </div>
</body>
</html>
