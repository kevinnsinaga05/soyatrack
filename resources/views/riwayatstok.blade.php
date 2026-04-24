@extends('layouts.app')

@section('title', 'Riwayat Stok Harian - GreSOY')

@section('content')
<div class="w-100 p-3 p-md-4">
    <div class="mb-4">
        <span class="fs-5 fw-semibold">Admin &gt; Stok &gt; Riwayat Harian</span>
    </div>

    <div class="panel-card">
        <div class="panel-body p-0">
            <div class="px-4 py-3 text-white fw-bold" style="background:#00c853;border-radius:18px 18px 0 0;">
                RIWAYAT STOK HARIAN
            </div>

            <form class="p-4" method="GET" action="{{ route('stok.riwayat') }}">
                <div class="d-flex flex-column flex-md-row gap-3 align-items-center justify-content-between"
                     style="border:1px solid #e5e7eb;border-radius:18px;padding:16px 20px;">
                    <div class="fw-semibold" style="min-width:120px;">Tanggal</div>

                    <input type="date" name="tanggal"
                           class="form-control rounded-pill px-4"
                           style="height:44px;max-width:320px;"
                           value="{{ $tanggal }}" required>

                    <button class="btn rounded-pill px-4 fw-semibold"
                            style="background:#00c853;color:#fff;min-width:140px;">
                        Tampilkan
                    </button>
                </div>
            </form>

            <div class="table-responsive px-4 pb-4">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nama Bahan</th>
                            <th style="width:200px;">Masuk</th>
                            <th style="width:200px;">Keluar / Terpakai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $r)
                            <tr>
                                <td class="fw-semibold">{{ $r->stokBahan->nama_bahan ?? '-' }}</td>
                                <td>{{ number_format($r->total_masuk, 2) }} {{ strtoupper($r->stokBahan->satuan ?? '') }}</td>
                                <td>{{ number_format($r->total_keluar, 2) }} {{ strtoupper($r->stokBahan->satuan ?? '') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    Belum ada mutasi stok pada tanggal ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="text-center pb-3 text-muted">
                “Keluar/Terpakai” termasuk: penjualan, tumpah/rusak, expired, opname selisih turun.
            </div>
        </div>
    </div>
</div>
@endsection
