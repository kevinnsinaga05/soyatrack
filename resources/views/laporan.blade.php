@extends('layouts.app')

@section('title', 'Laporan Laba Rugi - GreSOY')

@section('content')
<div class="w-100 p-3 p-md-4">

    <div class="mb-4">
        <span class="fs-5 fw-semibold">Admin &gt; Laporan Laba Rugi</span>
    </div>

    {{-- FILTER --}}
    <div class="panel-card mb-4">
        <div class="panel-body">
            <form method="GET" action="{{ route('laporan.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="fw-semibold">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $start }}" class="form-control rounded-pill px-3" required>
                </div>
                <div class="col-md-4">
                    <label class="fw-semibold">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $end }}" class="form-control rounded-pill px-3" required>
                </div>
                <div class="col-md-2">
                    <button class="btn rounded-pill px-4 fw-semibold w-100"
                            style="background:#00c853;color:#fff;">
                        Tampilkan
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('laporan.export.pdf', ['start_date' => $start, 'end_date' => $end]) }}"
                       class="btn rounded-pill px-4 fw-semibold w-100"
                       style="background:#0d6efd;color:#fff;">
                        Download PDF
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- RINGKASAN --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="summary-card total-produk">
                <div class="summary-card-inner">
                    <div class="summary-label">Total Produk Terjual</div>
                    <div class="summary-main">{{ $totalProdukTerjual }}</div>
                    <div class="summary-sub text-success">Produk terjual</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="summary-card total-penjualan">
                <div class="summary-card-inner">
                    <div class="summary-label">Total Omzet</div>
                    <div class="summary-main">Rp {{ number_format($totalOmzet,0,',','.') }}</div>
                    <div class="summary-sub text-primary">Omzet tercatat</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="summary-card stok-hampir-habis">
                <div class="summary-card-inner">
                    <div class="summary-label">HPP</div>
                    <div class="summary-main text-danger">Rp {{ number_format($hpp,0,',','.') }}</div>
                    <div class="summary-sub text-danger">Biaya Bahan</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="summary-card total-produk">
                <div class="summary-card-inner">
                    <div class="summary-label">Laba Kotor</div>
                    <div class="summary-main">Rp {{ number_format($labaKotor,0,',','.') }}</div>
                    <div class="summary-sub text-success">Estimasi laba</div>
                </div>
            </div>
        </div>
    </div>

    {{-- REKAP HARIAN --}}
    <div class="panel-card mb-4">
        <div class="panel-header">
            <div class="panel-title">Rekap Harian</div>
            <div class="panel-subtitle">Ringkasan jumlah terjual dan omzet per tanggal</div>
        </div>

        <div class="panel-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr class="text-muted small">
                            <th>Tanggal</th>
                            <th class="text-center">Total Qty</th>
                            <th class="text-end">Omzet Harian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekapHarian as $h)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($h['tanggal'])->translatedFormat('d M Y') }}</td>
                                <td class="text-center">{{ $h['qty'] }}</td>
                                <td class="text-end">Rp {{ number_format($h['omzet'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    Belum ada data pada rentang tanggal ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- REKAP PER PRODUK --}}
    <div class="panel-card mb-4">
        <div class="panel-header">
            <div class="panel-title">Rekap per Produk</div>
            <div class="panel-subtitle">Ringkasan performa tiap produk pada rentang tanggal</div>
        </div>

        <div class="panel-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr class="text-muted small">
                            <th>Produk</th>
                            <th class="text-center">Total Qty</th>
                            <th class="text-end">Harga Jual</th>
                            <th class="text-end">Total Omzet</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekapProduk as $r)
                            <tr>
                                <td>{{ $r['nama_produk'] }}</td>
                                <td class="text-center">{{ $r['qty'] }}</td>
                                <td class="text-end">Rp {{ number_format($r['harga'], 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($r['omzet'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Belum ada data pada rentang tanggal ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- DETAIL PENJUALAN --}}
    <div class="panel-card">
        <div class="panel-header">
            <div class="panel-title">Detail Penjualan</div>
            <div class="panel-subtitle">Riwayat produk terjual pada rentang tanggal</div>
        </div>

        <div class="panel-body">

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr class="text-muted small">
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penjualan as $p)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d M Y') }}</td>
                                <td>{{ $p->product->nama_produk ?? '-' }}</td>
                                <td class="text-center">{{ $p->jumlah_produk }}</td>
                                <td class="text-end">
                                    Rp {{ number_format($p->product->harga_jual ?? 0,0,',','.') }}
                                </td>
                                <td class="text-end">
                                    Rp {{ number_format(($p->jumlah_produk * ($p->product->harga_jual ?? 0)),0,',','.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada penjualan pada rentang tanggal ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
@endsection
