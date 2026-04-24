@extends('layouts.app')
@section('title', 'Detail Penjualan - GreSOY')

@section('content')
<div class="w-100 p-3 p-md-4">

    <div class="mb-4">
        <span class="fs-5 fw-semibold">Admin &gt; Update Produk &gt; Detail Penjualan</span>
    </div>

    <div class="panel-card">
        <div class="panel-body p-0">

            <div class="px-4 py-3 text-white fw-bold"
                 style="background:#00c853;border-radius:18px 18px 0 0;">
                DETAIL PENJUALAN
            </div>

            <div class="p-4">

                <div class="mb-3">
                    <div class="fw-semibold text-muted">Tanggal</div>
                    <div class="fw-bold">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</div>
                </div>

                <div class="mb-3">
                    <div class="fw-semibold text-muted">Total Terjual</div>
                    <div class="fw-bold">{{ $totalTerjual }}</div>
                </div>

                <hr>

                <div class="fw-bold mb-2">Detail Produk:</div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th width="150" class="text-center">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($details as $d)
                            <tr>
                                <td>{{ $d->product->nama_produk ?? '-' }}</td>
                                <td class="text-center">{{ $d->jumlah_produk }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">Belum ada data penjualan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-center gap-3 mt-4">
                    <a href="{{ route('update.stok') }}"
                       class="btn rounded-pill px-5 fw-semibold"
                       style="background:#00c853;color:#fff;min-width:140px;">
                        Kembali
                    </a>
                </div>

            </div>

        </div>
    </div>

</div>
@endsection
