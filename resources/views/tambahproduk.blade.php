@extends('layouts.app')

@section('title', 'Tambah Produk - GreSOY')

@section('content')
<div class="w-100 p-3 p-md-4">

    {{-- Breadcrumb --}}
    <div class="mb-4">
        <span class="fs-5 fw-semibold">Admin &gt; Produk &gt; Tambah Produk</span>
    </div>

    <style>
        .form-label-custom { font-weight: 600; font-size: 14px; color: #111827; }
        .input-rounded { border-radius: 999px; border: 1.5px solid #111; padding: 10px 16px; font-size: 14px; }
        .unit-text { font-weight: 600; color: #111827; min-width: 60px; text-align: left; }

        .btn-submit { background:#00c853; color:#fff; border-radius: 12px; padding: 10px 32px; font-weight: 600; border: none; }
        .btn-submit:hover { background:#00b44b; }

        .btn-cancel { background:#ff3b30; color:#fff; border-radius: 12px; padding: 10px 32px; font-weight: 600; border: none; }
        .btn-cancel:hover { background:#e13127; }

        .success-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.35);
            display:flex; align-items:center; justify-content:center;
            z-index:2000;
        }
        .success-modal {
            background:#fff; border-radius:28px; padding:34px 44px;
            max-width:640px; width:92%;
            box-shadow:0 24px 60px rgba(0,0,0,0.30);
            text-align:center;
        }
        .success-icon { font-size:72px; color:#00c853; margin-bottom:12px; }
        .success-title { font-size:28px; font-weight:700; margin-bottom:24px; color:#111827; line-height:1.2; }
        .btn-success-ok {
            background:#00c853; color:#fff; border-radius:999px;
            padding:10px 44px; font-weight:600; border:none;
        }
        .btn-success-ok:hover { background:#00b44b; }
    </style>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('produk.store') }}" method="POST" autocomplete="off">
    @csrf

    {{-- Nama Produk --}}
    <div class="mb-3">
        <label class="form-label-custom">Nama Produk</label>
        <input type="text" name="nama_produk"
               class="form-control input-rounded"
               value="{{ old('nama_produk') }}" required>
    </div>

    {{-- Harga Jual --}}
    <div class="mb-3">
        <label class="form-label-custom">Harga Jual</label>
        <div class="d-flex align-items-center gap-2">
            <input type="number" name="harga_jual"
                   class="form-control input-rounded"
                   value="{{ old('harga_jual', 0) }}"
                   min="0" required>
            <span class="unit-text">Rp</span>
        </div>
    </div>

    {{-- Jumlah Susu --}}
    <div class="mb-3">
        <label class="form-label-custom">Jumlah Susu</label>
        <div class="d-flex align-items-center gap-2">
            <input type="number" name="jumlah_susu"
                   class="form-control input-rounded"
                   value="{{ old('jumlah_susu', 0) }}"
                   min="0" required>
            <span class="unit-text">ML</span>
        </div>
    </div>

    {{-- Gula Biasa --}}
    <div class="mb-3">
        <label class="form-label-custom">Jumlah Gula (Biasa)</label>
        <div class="d-flex align-items-center gap-2">
            <input type="number" name="jumlah_gula"
                   class="form-control input-rounded"
                   value="{{ old('jumlah_gula', 0) }}"
                   min="0" required>
            <span class="unit-text">Gram</span>
        </div>
    </div>

    {{-- Gula Tropicana --}}
    <div class="mb-3">
        <label class="form-label-custom">Jumlah Gula Tropicana</label>
        <div class="d-flex align-items-center gap-2">
            <input type="number" name="jumlah_gula_tropicana"
                   class="form-control input-rounded"
                   value="{{ old('jumlah_gula_tropicana', 0) }}"
                   min="0" required>
            <span class="unit-text">Sachet</span>
        </div>
        <small class="text-muted ms-2">Isi 0 jika produk tidak memakai tropicana</small>
    </div>

    {{-- Jenis Powder --}}
    <div class="mb-3">
        <label class="form-label-custom">Jenis Powder</label>
        @php
            $powderOptions = collect(['Coklat', 'Vanilla', 'Matcha', 'Taro'])
                ->merge(($powders ?? collect())->pluck('nama_bahan'))
                ->map(fn ($name) => trim((string) $name))
                ->filter()
                ->unique()
                ->values();
        @endphp
        <select name="jenis_powder" class="form-select input-rounded" required>
            <option value="">Pilih Powder</option>
            @foreach($powderOptions as $powderName)
                <option value="{{ $powderName }}" {{ old('jenis_powder') == $powderName ? 'selected' : '' }}>
                    {{ $powderName }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Jumlah Powder --}}
    <div class="mb-4">
        <label class="form-label-custom">Jumlah Powder</label>
        <div class="d-flex align-items-center gap-2">
            <input type="number" name="jumlah_powder"
                   class="form-control input-rounded"
                   value="{{ old('jumlah_powder', 0) }}"
                   min="0" required>
            <span class="unit-text">Gram</span>
        </div>
    </div>

    {{-- Tombol --}}
    <div class="d-flex gap-3 mt-4">
        <button type="submit" class="btn-submit">Submit</button>

        <a href="{{ route('produk.index') }}"
           class="btn-cancel text-decoration-none d-inline-flex align-items-center justify-content-center">
            Cancel
        </a>
    </div>
</form>

        </div>
    </div>

    {{-- POPUP SUKSES --}}
    @if (session('success'))
        <div class="success-overlay" id="successOverlay">
            <div class="success-modal">
                <div class="success-icon"><i class="bi bi-check-circle-fill"></i></div>
                <div class="success-title">Produk Berhasil Ditambahkan</div>
                <button type="button" class="btn-success-ok" id="successOkBtn">OKE</button>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const go = () => window.location.href = "{{ route('produk.index') }}";
                document.getElementById('successOkBtn')?.addEventListener('click', go);
                setTimeout(go, 2500);
            });
        </script>
    @endif

</div>
@endsection
