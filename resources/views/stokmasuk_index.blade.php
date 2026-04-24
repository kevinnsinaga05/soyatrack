@extends('layouts.app')

@section('title', 'Stok Masuk - GreSOY')

@section('content')
<div class="w-100 p-3 p-md-4">

    <div class="mb-4">
        <span class="fs-5 fw-semibold">Admin &gt; Stok Masuk</span>
    </div>

    {{-- Header Hijau --}}
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2"
             style="border-radius:18px;background:#00c853;
             box-shadow:0 18px 40px rgba(15,23,42,0.10);
             padding:14px 20px;">
            <div class="fw-bold text-white">STOK MASUK</div>

            <a href="{{ route('stok.masuk.create') }}"
               class="btn btn-light fw-semibold rounded-pill px-4 py-1">
                Tambah Stok Masuk
            </a>
        </div>
    </div>

    {{-- Alert success / error --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <style>
        /* ✅ responsive card row untuk mobile */
        .stok-card {
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            padding: 16px 18px;
            background: #fff;
            margin-bottom: 12px;
        }
        .stok-label {
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
        }
        .stok-value {
            font-weight: 600;
            color: #111827;
        }
        .stok-table-row {
            padding: 16px 6px;
        }
    </style>

    {{-- Panel --}}
    <div class="panel-card mt-3">
        <div class="panel-body">

            {{-- Header kolom (desktop only) --}}
            <div class="d-none d-md-flex px-2 pb-2 border-bottom">
                <div class="flex-grow-1 fw-semibold">Tanggal</div>
                <div class="fw-semibold" style="width:240px;">Bahan</div>
                <div class="fw-semibold text-center" style="width:140px;">Qty Masuk</div>

                {{-- Expired tooltip --}}
                <div class="fw-semibold text-center" style="width:140px;">
                    Expired
                    <i class="bi bi-info-circle-fill text-muted ms-1"
                       data-bs-toggle="tooltip"
                       data-bs-placement="top"
                       title="Tanggal expired diisi hanya jika bahan/powder memiliki masa kadaluarsa"></i>
                </div>

                <div class="fw-semibold text-center" style="width:160px;">Harga</div>
                <div class="fw-semibold text-center" style="width:140px;">Aksi</div>
            </div>

            {{-- keterangan tambahan (desktop only) --}}
            <div class="d-none d-md-block text-muted small px-2 pt-2 pb-3 border-bottom">
                <i class="bi bi-exclamation-circle-fill text-warning me-1"></i>
                Kolom <b>Expired</b> adalah tanggal kadaluarsa bahan/powder (diisi jika barang memiliki masa expired).
            </div>

            @forelse($rows as $r)

                {{-- ============================
                    ✅ DESKTOP VIEW
                ============================ --}}
                <div class="d-none d-md-flex flex-column flex-md-row align-items-center justify-content-between border-bottom stok-table-row">

                    <div class="flex-grow-1 fw-semibold text-dark">
                        {{ \Carbon\Carbon::parse($r->tanggal_masuk)->translatedFormat('d F Y') }}
                    </div>

                    <div class="fw-semibold text-dark" style="width:240px;">
                        {{ $r->stokBahan->nama_bahan ?? '-' }}
                    </div>

                    <div class="text-center fw-semibold" style="width:140px;">
                        <span class="text-success">
                            +{{ rtrim(rtrim(number_format($r->qty_in, 2, '.', ''), '0'), '.') }}
                        </span>
                    </div>

                    <div class="text-center" style="width:140px;">
                        @if($r->expired_at)
                            {{ \Carbon\Carbon::parse($r->expired_at)->translatedFormat('d M Y') }}
                        @else
                            -
                        @endif
                    </div>

                    <div class="text-center" style="width:160px;">
                        @if($r->harga_beli_per_satuan)
                            Rp {{ number_format($r->harga_beli_per_satuan, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </div>

                    <div class="text-center" style="width:140px;">
                        <form action="{{ route('stok.masuk.destroy', $r->id) }}"
                              method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus stok masuk ini? Stok akan dikembalikan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 fw-semibold">
                                Hapus
                            </button>
                        </form>
                    </div>

                </div>

                {{-- ============================
                    ✅ MOBILE VIEW (CARD)
                ============================ --}}
                <div class="d-md-none stok-card">

                    <div class="mb-2">
                        <div class="stok-label">Tanggal</div>
                        <div class="stok-value">
                            {{ \Carbon\Carbon::parse($r->tanggal_masuk)->translatedFormat('d F Y') }}
                        </div>
                    </div>

                    <div class="mb-2">
                        <div class="stok-label">Bahan</div>
                        <div class="stok-value">
                            {{ $r->stokBahan->nama_bahan ?? '-' }}
                        </div>
                    </div>

                    <div class="mb-2">
                        <div class="stok-label">Qty Masuk</div>
                        <div class="stok-value text-success">
                            +{{ rtrim(rtrim(number_format($r->qty_in, 2, '.', ''), '0'), '.') }}
                        </div>
                    </div>

                    <div class="mb-2">
                        <div class="stok-label">Expired</div>
                        <div class="stok-value">
                            @if($r->expired_at)
                                {{ \Carbon\Carbon::parse($r->expired_at)->translatedFormat('d M Y') }}
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="stok-label">Harga</div>
                        <div class="stok-value">
                            @if($r->harga_beli_per_satuan)
                                Rp {{ number_format($r->harga_beli_per_satuan, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('stok.masuk.destroy', $r->id) }}"
                          method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus stok masuk ini? Stok akan dikembalikan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100 rounded-pill fw-semibold">
                            Hapus
                        </button>
                    </form>

                </div>

            @empty
                <div class="text-center py-4 text-muted">
                    Belum ada data stok masuk.
                </div>
            @endforelse

            {{-- pagination --}}
            <div class="mt-3">
                {{ $rows->links() }}
            </div>

        </div>
    </div>

</div>

{{-- Tooltip --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection
