@extends('layouts.app')

@section('title', 'Dashboard - GreSOY')

@section('content')
<div class="w-100 p-3 p-md-4">

    <div class="mb-4">
        <span class="fs-5 fw-semibold">Admin &gt; Dashboard</span>
    </div>

    <style>
        /* ===== SUMMARY CARD ===== */
        .summary-card {
            border-radius: 22px;
            padding: 22px 24px;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 10px 26px rgba(15,23,42,0.06);
            background: #fff;
            height: 100%;
            transition: .2s ease;
        }
        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 32px rgba(15,23,42,0.08);
        }

        /* warna Soft gradient */
        .summary-card.total-produk {
            background: linear-gradient(135deg, #eafff1, #f6fff9);
        }
        .summary-card.total-penjualan {
            background: linear-gradient(135deg, #edf5ff, #f9fcff);
        }
        .summary-card.stok-hampir-habis {
            background: linear-gradient(135deg, #fff3f3, #fffaf9);
        }

        .summary-label {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            display:flex;
            justify-content: space-between;
            align-items:center;
        }

        /* angka */
        .summary-main {
            font-size: 32px;
            font-weight: 700;
            margin-top: 10px;
            color:#111827;
        }

        .summary-sub {
            font-size: 13px;
            margin-top: 4px;
            font-weight: 600;
            color:#16a34a;
        }
        .summary-sub.blue { color:#2563eb; }
        .summary-sub.red { color:#ef4444; }

        /* ===== PANEL CARD ===== */
        .panel-card {
            border-radius: 22px;
            background: #fff;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 10px 26px rgba(15,23,42,0.06);
            overflow: hidden;
        }

        .panel-header {
            padding: 18px 22px;
            border-bottom: 1px solid #eef2f7;
        }

        .panel-title {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }

        .panel-subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-top: 3px;
        }

        .panel-body {
            padding: 18px 22px;
        }

        /* ===== LIST STOK ===== */
        .stok-row {
            padding: 14px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .stok-row:last-child { border-bottom: none; }

        .stok-row .stok-title {
            font-weight: 700;
            color:#111827;
        }

        .stok-row .stok-desc {
            font-size: 13px;
            color:#6b7280;
            margin-top: 4px;
        }

        /* Badge */
        .badge-sisa {
            background: #ffe8e8;
            color: #ff3b30;
            font-weight: 600;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 12px;
            white-space: nowrap;
        }

        /* ===== FOOTER LINK ===== */
        .panel-footer-link {
            text-align:center;
            padding: 14px 0;
            border-top:1px solid #eef2f7;
        }
        .panel-footer-link a {
            color:#00c853;
            font-weight:600;
            text-decoration:none;
        }

        /* ===== RINGKASAN ===== */
        .ringkasan-row {
            display:flex;
            justify-content: space-between;
            align-items:center;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }
        .ringkasan-row:last-child { border-bottom:none; }

        .ringkasan-row strong {
            font-weight: 700;
            color:#111827;
        }

        /* ===== TIPS PRODUKSI ===== */
        .tips-card {
            border-radius: 22px;
            padding: 24px 22px;
            color:#fff;
            background: linear-gradient(135deg, #14b8a6, #0ea5e9);
            box-shadow: 0 10px 26px rgba(15,23,42,0.08);
        }
        .tips-title { font-size: 20px; font-weight: 700; margin-bottom: 6px; }
        .tips-text { font-size: 14px; opacity:0.95; margin-bottom: 16px; line-height: 1.4; }

        .tips-btn {
            background:#fff;
            color:#111827;
            border-radius: 12px;
            padding: 10px 16px;
            font-weight: 700;
            text-decoration:none;
            display:inline-block;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .summary-main { font-size: 28px; }
            .panel-title { font-size: 16px; }
        }
    </style>

    {{-- SUMMARY --}}
    <div class="row g-3">
        <div class="col-md-4">
            <div class="summary-card total-produk">
                <div class="summary-label">
                    <span>Total Produk</span>
                    <i class="bi bi-box-seam text-success fs-5"></i>
                </div>
                <div class="summary-main">{{ $totalProduk }}</div>
                <div class="summary-sub">{{ $totalProduk }} Tersedia</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="summary-card total-penjualan">
                <div class="summary-label">
                    <span>Jumlah Penjualan</span>
                    <i class="bi bi-graph-up-arrow text-primary fs-5"></i>
                </div>
                <div class="summary-main">{{ $totalPenjualan }}</div>
                <div class="summary-sub blue">Penjualan tercatat</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="summary-card stok-hampir-habis">
                <div class="summary-label">
                    <span>Stok Hampir Habis</span>
                    <i class="bi bi-exclamation-triangle text-danger fs-5"></i>
                </div>
                <div class="summary-main">{{ $jumlahHampirHabis }}</div>
                <div class="summary-sub red">Perlu segera dicek</div>
            </div>
        </div>
    </div>

    {{-- ✅ GRID FIXED (kiri 8, kanan 4) --}}
    <div class="row g-4 mt-3">

        {{-- ✅ KIRI --}}
        <div class="col-xl-8">

            {{-- PANEL STOK HAMPIR HABIS --}}
            <div class="panel-card">
                <div class="panel-header d-flex justify-content-between align-items-center">
                    <div>
                        <div class="panel-title">Stok Hampir Habis</div>
                        <div class="panel-subtitle">Daftar bahan/powder dengan stok yang mulai menipis</div>
                    </div>
                    <div class="fw-semibold text-muted">Status Stok</div>
                </div>

                <div class="panel-body">
                    @if($stokHampirHabisList->isEmpty())
                        <div class="text-center text-muted py-4">
                            Semua stok aman
                        </div>
                    @else
                        @foreach($stokHampirHabisList as $stok)
                            <div class="stok-row d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stok-title">{{ $stok->nama_bahan }}</div>
                                    <div class="stok-desc">
                                        Batas minimum: {{ $stok->stok_minimum }} {{ strtoupper($stok->satuan) }}
                                    </div>
                                </div>

                                <span class="badge-sisa">
                                    Sisa {{ $stok->total_stok }} {{ strtoupper($stok->satuan) }}
                                </span>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="panel-footer-link">
                    <a href="{{ route('stok.index') }}">Lihat detail stok »</a>
                </div>
            </div>

            {{-- PANEL EXPIRED --}}
            <div class="panel-card mt-4">
                <div class="panel-header d-flex justify-content-between align-items-center">
                    <div>
                        <div class="panel-title">Notifikasi Expired</div>
                        <div class="panel-subtitle">Bahan yang akan expired dalam 7 hari</div>
                    </div>
                    <div class="fw-semibold text-muted">Expired</div>
                </div>

                <div class="panel-body">
                    @if($expiredSoon->isEmpty())
                        <div class="text-center text-muted py-4">
                            Tidak ada bahan yang mendekati expired 
                        </div>
                    @else
                        @foreach($expiredSoon as $b)
                            <div class="stok-row d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stok-title">{{ $b->stokBahan->nama_bahan }}</div>
                                    <div class="stok-desc">
                                        Expired: {{ \Carbon\Carbon::parse($b->expired_at)->translatedFormat('d M Y') }}
                                    </div>
                                </div>

                                <span class="badge-sisa" style="background:#fff7ed;color:#f97316;">
                                    Sisa {{ $b->qty_remaining }} {{ strtoupper($b->stokBahan->satuan ?? '') }}
                                </span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>

        {{-- ✅ KANAN --}}
        <div class="col-xl-4">

            {{-- Ringkasan --}}
            <div class="panel-card mb-4">
                <div class="panel-header">
                    <div class="panel-title">Ringkasan Stok</div>
                    <div class="panel-subtitle">Gambaran singkat stok bahan</div>
                </div>

                <div class="panel-body">
                    <div class="ringkasan-row">
                        <span>Total Produk</span>
                        <strong>{{ $totalProduk }}</strong>
                    </div>
                    <div class="ringkasan-row">
                        <span>Total Penjualan</span>
                        <strong>{{ $totalPenjualan }}</strong>
                    </div>
                    <div class="ringkasan-row text-danger">
                        <span>Stok Hampir Habis</span>
                        <strong>{{ $jumlahHampirHabis }}</strong>
                    </div>
                </div>
            </div>

            {{-- Tips --}}
            <div class="tips-card">
                <div class="tips-title">Tips Produksi</div>
                <div class="tips-text">
                    Cek stok harian sebelum produksi untuk menghindari kehabisan bahan.
                </div>

                <a href="{{ route('stok.index') }}" class="tips-btn">
                    Lihat Rekomendasi
                </a>
            </div>

        </div>

    </div>

</div>
@endsection
