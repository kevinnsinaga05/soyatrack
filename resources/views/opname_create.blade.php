@extends('layouts.app')

@section('title', 'Opname / Kalibrasi - GreSOY')

@section('content')
<div class="w-100 p-3 p-md-4">
    <div class="mb-4">
        <span class="fs-5 fw-semibold">Admin &gt; Stok &gt; Opname/Kalibrasi</span>
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
                OPNAME / KALIBRASI
            </div>

            @if(session('error'))
                <div class="alert alert-danger m-3">{{ session('error') }}</div>
            @endif

            <form action="{{ route('stok.opname.store') }}" method="POST" class="p-4">
                @csrf

                <div class="mb-4">
                    <div class="d-flex flex-column flex-md-row gap-3 align-items-center justify-content-between"
                         style="border:1px solid #e5e7eb;border-radius:18px;padding:16px 20px;">
                        <div class="fw-semibold" style="min-width:120px;">Tanggal Kalibrasi</div>

                        <input type="date" name="tanggal"
                               class="form-control rounded-pill px-4"
                               style="height:44px;max-width:320px;"
                               value="{{ old('tanggal', date('Y-m-d')) }}"
                               required>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nama Bahan</th>
                                <th style="width:200px;">Stok Sistem</th>
                                <th style="width:260px;">Stok Aktual (input)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bahan as $b)
                                <tr>
                                    <td class="fw-semibold">{{ $b->nama_bahan }}</td>
                                    <td>{{ $b->total_stok }} {{ strtoupper($b->satuan) }}</td>
                                    <td>
                                        <input type="number" step="0.01" min="0"
                                               name="actual[{{ $b->id }}]"
                                               class="form-control rounded-pill px-4"
                                               placeholder="Isi stok aktual">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center gap-3 mt-4">
                    <button type="submit" class="btn rounded-pill px-5 fw-semibold"
                            style="background:#00c853;color:#fff;min-width:140px;">
                        Simpan Opname
                    </button>
                </div>

                <div class="text-muted text-center mt-3">
                    Sistem akan otomatis membuat mutasi selisih (naik/turun) agar stok sistem = stok aktual.
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="success-overlay" id="successOverlay">
            <div class="success-modal">
                <div class="success-icon"><i class="bi bi-check-circle-fill"></i></div>
                <div class="success-title">Opname berhasil disimpan</div>
                <button type="button" class="btn-success-ok" id="successOkBtn">OKE</button>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function(){
                const go = () => window.location.href = "{{ route('stok.riwayat', ['tanggal' => old('tanggal', date('Y-m-d'))]) }}";
                document.getElementById('successOkBtn')?.addEventListener('click', go);
                setTimeout(go, 2500);
            });
        </script>
    @endif
</div>
@endsection
