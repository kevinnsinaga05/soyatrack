<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .title { text-align:center; font-size: 18px; font-weight: bold; margin-bottom: 10px; }
        .subtitle { text-align:center; margin-bottom: 20px; }
        table { width:100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border:1px solid #333; padding: 8px; }
        th { background:#f2f2f2; font-weight:bold; }
        .footer { margin-top: 30px; text-align:right; font-size: 11px; color:#555; }
    </style>
</head>
<body>

    <div class="title">LAPORAN PENJUALAN GRESSOY PURWOKERTO</div>
    <div class="subtitle">Tanggal: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</div>

    <table>
        <thead>
            <tr>
                <th style="width:40px;">No</th>
                <th>Nama Produk</th>
                <th style="width:120px;">Jumlah Terjual</th>
            </tr>
        </thead>
        <tbody>
            @forelse($updates as $i => $u)
                <tr>
                    <td style="text-align:center;">{{ $i+1 }}</td>
                    <td>{{ $u->product->nama_produk ?? '-' }}</td>
                    <td style="text-align:center;">{{ $u->jumlah_produk }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align:center;">Belum ada update penjualan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}
    </div>

</body>
</html>
