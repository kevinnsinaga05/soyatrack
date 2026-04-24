<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Laba Rugi</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #111827;
            font-size: 12px;
        }
        .header {
            margin-bottom: 16px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .subtitle {
            font-size: 12px;
            color: #374151;
        }
        .summary {
            width: 100%;
            border-collapse: collapse;
            margin: 14px 0 18px 0;
        }
        .summary td {
            border: 1px solid #d1d5db;
            padding: 8px;
        }
        .summary .label {
            width: 35%;
            font-weight: bold;
            background: #f3f4f6;
        }
        table.detail {
            width: 100%;
            border-collapse: collapse;
        }
        table.detail th,
        table.detail td {
            border: 1px solid #d1d5db;
            padding: 7px;
        }
        table.detail th {
            background: #e5e7eb;
            text-align: left;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Rekap Laporan Laba Rugi</div>
        <div class="subtitle">
            Periode {{ \Carbon\Carbon::parse($start)->translatedFormat('d M Y') }}
            s.d.
            {{ \Carbon\Carbon::parse($end)->translatedFormat('d M Y') }}
        </div>
    </div>

    <table class="summary">
        <tr>
            <td class="label">Total Produk Terjual</td>
            <td>{{ $totalProdukTerjual }}</td>
        </tr>
        <tr>
            <td class="label">Total Omzet</td>
            <td>Rp {{ number_format($totalOmzet, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">HPP</td>
            <td>Rp {{ number_format($hpp, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Laba Kotor</td>
            <td>Rp {{ number_format($labaKotor, 0, ',', '.') }}</td>
        </tr>
    </table>

    <table class="detail">
        <thead>
            <tr>
                <th style="width: 20%;">Tanggal</th>
                <th>Produk</th>
                <th style="width: 10%;" class="text-center">Qty</th>
                <th style="width: 18%;" class="text-end">Harga</th>
                <th style="width: 18%;" class="text-end">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penjualan as $p)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d M Y') }}</td>
                    <td>{{ $p->product->nama_produk ?? '-' }}</td>
                    <td class="text-center">{{ $p->jumlah_produk }}</td>
                    <td class="text-end">Rp {{ number_format($p->product->harga_jual ?? 0, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format(($p->jumlah_produk * ($p->product->harga_jual ?? 0)), 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data penjualan pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
