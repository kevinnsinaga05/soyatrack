@extends('layouts.app')

@section('title', 'Penyesuaian Stok - GreSOY')

@section('content')
<div class="w-100 p-3 p-md-4">
    <div class="mb-4">
        <span class="fs-5 fw-semibold">Admin &gt; Stok &gt; Penyesuaian</span>
    </div>

    <style>
        .success-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.25);display:flex;align-items:center;justify-content:center;z-index:2000;}
        .success-modal{background:#fff;border-radius:28px;padding:32px 40px;max-width:640px;width:90%;box-shadow:0 24px 60px rgba(0,0,0,0.25);text-align:center;}
        .success-icon{font-size:72px;color:#00c853;margin-bottom:12px;}
        .success-title{font-size:26px;font-weight:700;margin-bottom:20px;color:#111827;}
        .btn-success-ok{background:#00c853;color:#fff;border-radius:999px;padding:10px 40px;font-weight:600;border:none;}
    </style>

    <div class="panel-card">
        <div class="panel-body p-0">
            <div class="px-4 py-3 text-white fw-bold" style="background:#00c853;border-radius:18px 18px 0 0;">
                PENYESUAIAN STOK
            </div>

            @if(session('error'))
                <div class="alert alert-danger m-3">{{ session('error') }}</div>
            @endif

            <form action="{{ route('stok.adjust.store') }}" method="POST" class="p-4">
                @csrf

                <div class="mb-3">
                    <label class="fw-semibold mb-2">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control rounded-pill px-4" style="height:44px;"
                           value="{{ old('tanggal', date('Y-m-d')) }}" required>
                </div>

                <div class="mb-3">
                    <label class="fw-semibold mb-2">Bahan</label>
                    <select name="stok_bahan_id" class="form-select rounded-pill px-4" style="height:44px;" required>
                        <option value="">Pilih Bahan</option>
                        @foreach($bahan as $b)
                            <option value="{{ $b->id }}">{{ $b->nama_bahan }} ({{ strtoupper($b->satuan) }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="fw-semibold mb-2">Arah</label>
                    <select name="arah" class="form-select rounded-pill px-4" style="height:44px;" required>
                        <option value="OUT">Kurangi (Tumpah/Rusak/Hilang/Expired)</option>
                        <option value="IN">Tambah (Koreksi / Ditemukan)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="fw-semibold mb-2">Jumlah</label>
                    <input type="number" step="0.01" min="0.01"
                           name="qty" class="form-control rounded-pill px-4" style="height:44px;"
                           placeholder="Jumlah penyesuaian" required>
                </div>

                <div class="mb-4">
                    <label class="fw-semibold mb-2">Alasan</label>
                    <select name="alasan" class="form-select rounded-pill px-4" style="height:44px;" required>
                        <option value="Tumpah">Tumpah</option>
                        <option value="Rusak">Rusak</option>
                        <option value="Hilang">Hilang</option>
                        <option value="Expired">Expired</option>
                        <option value="Koreksi">Koreksi</option>
                    </select>
                    
                </div>

                <div class="d-flex justify-content-center gap-3">
                    <button type="submit" class="btn rounded-pill px-5 fw-semibold"
                            style="background:#00c853;color:#fff;min-width:140px;">
                        Submit
                    </button>

                    <a href="{{ route('stok.adjust.index') }}" class="btn rounded-pill px-5 fw-semibold"
                       style="background:#ff3b30;color:#fff;min-width:140px;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="success-overlay" id="successOverlay">
            <div class="success-modal">
                <div class="success-icon"><i class="bi bi-check-circle-fill"></i></div>
                <div class="success-title">Penyesuaian berhasil disimpan</div>
                <button type="button" class="btn-success-ok" id="successOkBtn">OKE</button>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function(){
                const go = () => window.location.href = "{{ route('stok.adjust.index') }}";
                document.getElementById('successOkBtn')?.addEventListener('click', go);
                setTimeout(go, 2500);
            });
        </script>
    @endif
</div>
@endsection
