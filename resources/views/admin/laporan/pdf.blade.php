<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekapitulasi Donasi - Yayasan Himatun Ayat Bandung</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333333;
            font-size: 11px;
            line-height: 1.4;
            margin: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2D6A4F;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #2D6A4F;
            font-size: 18px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 3px 0 0;
            color: #555555;
            font-size: 10px;
        }
        .title-block {
            margin-bottom: 15px;
        }
        .title-block h2 {
            font-size: 14px;
            color: #1b4332;
            margin: 0 0 4px;
        }
        .title-block p {
            color: #666;
            margin: 0;
            font-size: 10px;
        }
        .summary-table {
            width: 100%;
            margin-bottom: 20px;
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
            font-weight: bold;
            font-size: 10px;
            padding: 6px 8px;
            text-align: left;
            border: 1px solid #2D6A4F;
        }
        table.data-table td {
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
            font-size: 10px;
            vertical-align: top;
        }
        table.data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .nominal {
            text-align: right;
            font-weight: bold;
            color: #1b4332;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
        }
        .signature-box {
            display: inline-block;
            text-align: center;
            width: 180px;
        }
        .signature-line {
            margin-top: 50px;
            border-top: 1px solid #333;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>YAYASAN HIMATUN AYAT BANDUNG</h1>
        <p>Jl. Cibiru Indah 7 RT/RW 04/14 Des. Cibiru Wetan, Kec. Cileunyi, Kab. Bandung</p>
        <p>Telepon / WhatsApp: +62 812-2334-455 &bull; Email: admin@himmatunayat.org</p>
    </div>

    <div class="title-block">
        <h2>LAPORAN REKAPITULASI DONASI TERVERIFIKASI</h2>
        <p>
            Periode: 
            {{ $startDate ? \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') : 'Awal' }} 
            s/d 
            {{ $endDate ? \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') : 'Sekarang' }}
            &bull; Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }} WIB
        </p>
    </div>

    <table class="summary-table">
        <tr>
            <td width="50%" style="padding-right: 6px;">
                <div class="summary-box">
                    <div class="summary-title">Total Donasi Terverifikasi</div>
                    <div class="summary-val">Rp{{ number_format($summary['total_donasi_terverifikasi'], 0, ',', '.') }}</div>
                </div>
            </td>
            <td width="50%" style="padding-left: 6px;">
                <div class="summary-box">
                    <div class="summary-title">Total Transaksi</div>
                    <div class="summary-val">{{ $summary['total_transaksi_verified'] }} Transaksi</div>
                </div>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="12%">Tanggal</th>
                <th width="20%">Nama Donatur</th>
                <th width="14%">Kontak (WA)</th>
                <th width="22%">Program Kampanye</th>
                <th width="15%">Nominal</th>
                <th width="13%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($donasi as $index => $item)
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td><strong>{{ $item->nama_donatur }}</strong></td>
                    <td>{{ $item->no_whatsapp ?: '-' }}</td>
                    <td>{{ $item->kampanye?->judul ?? 'Donasi Operasional Yayasan' }}</td>
                    <td class="nominal">Rp{{ number_format($item->nominal, 0, ',', '.') }}</td>
                    <td align="center">Terverifikasi</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" align="center" style="padding: 15px; color: #888;">
                        Tidak ada data donasi terverifikasi pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($donasi->count() > 0)
            <tfoot>
                <tr style="background-color: #f1f5f9; font-weight: bold;">
                    <td colspan="5" align="right" style="padding: 8px;">TOTAL KESELURUHAN:</td>
                    <td class="nominal" style="padding: 8px;">Rp{{ number_format($donasi->sum('nominal'), 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        @endif
    </table>

    <div class="footer">
        <div class="signature-box">
            <p>Bandung, {{ now()->translatedFormat('d F Y') }}</p>
            <p style="margin-top: -8px;">Pengurus Yayasan</p>
            <div class="signature-line">
                Admin Yayasan
            </div>
        </div>
    </div>
</body>
</html>
