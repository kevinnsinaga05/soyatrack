@extends('layouts.app')

@section('title', 'Penyesuaian Stok - GreSOY')

@section('content')
<div class="w-100 p-3 p-md-4">

    <div class="mb-4">
        <span class="fs-5 fw-semibold">Admin &gt; Stok &gt; Penyesuaian</span>
    </div>

    {{-- Header --}}
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2"
             style="border-radius:18px;background:#00c853;box-shadow:0 18px 40px rgba(15,23,42,0.10);padding:14px 20px;">
            <div class="fw-bold text-white">PENYESUAIAN STOK</div>

            <a href="{{ route('stok.adjust.create') }}"
               class="btn btn-light fw-semibold rounded-pill px-4 py-1">
                Tambah Penyesuaian
            </a>
        </div>
    </div>

    <style>
        /* ✅ card style untuk mobile */
        .adjust-card {
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            padding: 16px 18px;
            background: #fff;
            margin-bottom: 14px;
            box-shadow: 0 10px 25px rgba(15,23,42,0.06);
        }
        .adjust-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 3px;
        }
        .adjust-value {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
        }
    </style>

    {{-- Panel --}}
    <div class="panel-card mt-3">
        <div class="panel-body">

            {{-- Header kolom (desktop only) --}}
            <div class="d-none d-md-flex px-2 pb-2 border-bottom">
                <div class="flex-grow-1 fw-semibold">Tanggal</div>
                <div class="fw-semibold" style="width:250px;">Bahan</div>
                <div class="fw-semibold text-center" style="width:140px;">Qty</div>
                <div class="fw-semibold text-center" style="width:200px;">Keterangan</div>
            </div>

            @forelse($rows as $r)

                @php
                    $satuan = strtoupper($r->stokBahan->satuan ?? '');
                    $qty = $r->qty;
                @endphp

                {{-- ✅ DESKTOP VIEW --}}
                <div class="d-none d-md-flex flex-column flex-md-row align-items-center justify-content-between py-3 px-2 border-bottom">

                    <div class="flex-grow-1 fw-semibold text-dark">
                        {{ \Carbon\Carbon::parse($r->tanggal)->translatedFormat('d F Y') }}
                    </div>

                    <div class="fw-semibold text-dark" style="width:250px;">
                        {{ $r->stokBahan->nama_bahan ?? '-' }}
                    </div>

                    <div class="text-center fw-semibold" style="width:140px;">
                        @if($qty > 0)
                            <span class="text-success">+{{ $qty }} {{ $satuan }}</span>
                        @else
                            <span class="text-danger">{{ $qty }} {{ $satuan }}</span>
                        @endif
                    </div>

                    <div class="text-center text-muted" style="width:200px;">
                        {{ $r->keterangan ?? '-' }}
                    </div>
                </div>

                {{-- ✅ MOBILE VIEW --}}
                <div class="d-md-none adjust-card">
                    
                    <div class="mb-2">
                        <div class="adjust-label">Tanggal</div>
                        <div class="adjust-value">
                            {{ \Carbon\Carbon::parse($r->tanggal)->translatedFormat('d F Y') }}
                        </div>
                    </div>

                    <div class="mb-2">
                        <div class="adjust-label">Bahan</div>
                        <div class="adjust-value">
                            {{ $r->stokBahan->nama_bahan ?? '-' }}
                        </div>
                    </div>

                    <div class="mb-2">
                        <div class="adjust-label">Qty</div>
                        <div class="adjust-value">
                            @if($qty > 0)
                                <span class="text-success">+{{ $qty }} {{ $satuan }}</span>
                            @else
                                <span class="text-danger">{{ $qty }} {{ $satuan }}</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="adjust-label">Keterangan</div>
                        <div class="adjust-value text-muted">
                            {{ $r->keterangan ?? '-' }}
                        </div>
                    </div>

                </div>

            @empty
                <div class="text-center py-4 text-muted">
                    Belum ada penyesuaian stok.
                </div>
            @endforelse

            <div class="mt-3">
                {{ $rows->links() }}
            </div>

        </div>
    </div>

</div>
@endsection
