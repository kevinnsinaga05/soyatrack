@extends('layouts.app')

@section('title', 'Edit Stok - GreSOY')

@section('content')
    <div class="w-100 p-3 p-md-4">

        <div class="mb-4">
            <span class="fs-5 fw-semibold">Admin &gt; Stok Bahan &gt; Edit Stok</span>
        </div>

        <style>
            .form-label-custom { font-weight: 600; font-size: 14px; color: #111827; }
            .input-rounded { border-radius: 999px; border: 1.5px solid #111; padding: 10px 16px; font-size: 14px; }
            .btn-submit { background:#00c853; color:#fff; border-radius: 12px; padding: 10px 32px; font-weight: 600; border: none; }
            .btn-cancel { background:#ff3b30; color:#fff; border-radius: 12px; padding: 10px 32px; font-weight: 600; border: none; }

            .success-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.25); display:flex; align-items:center; justify-content:center; z-index:2000; }
            .success-modal { background:#fff; border-radius: 28px; padding: 32px 40px; max-width: 640px; width: 90%; box-shadow: 0 24px 60px rgba(0,0,0,0.25); text-align:center; }
            .success-icon { font-size: 72px; color:#00c853; margin-bottom: 12px; }
            .success-title { font-size: 28px; font-weight: 700; margin-bottom: 24px; color:#111827; }
            .btn-success-ok { background:#00c853; color:#fff; border-radius: 999px; padding: 10px 40px; font-weight: 600; border: none; }
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

                <form action="{{ route('stok.update', $stokBahan->id) }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT')

                    @php
                        $kategoriValue = old('kategori', $stokBahan->kategori);
                        $kategoriOptions = ['SUSU', 'GULA', 'TROPICANA', 'POWDER', 'BAHAN BAKU', 'PACKAGING', 'PERLENGKAPAN', 'KEBERSIHAN', 'LAINNYA'];
                    @endphp

                    {{-- Nama Bahan --}}
                    <div class="mb-3">
                        <label class="form-label-custom" for="nama_bahan">Nama Bahan</label>
                        <input type="text"
                               name="nama_bahan"
                               id="nama_bahan"
                               class="form-control input-rounded"
                               value="{{ old('nama_bahan', $stokBahan->nama_bahan) }}"
                               required>
                    </div>

                    {{-- Kategori --}}
                    <div class="mb-3">
                        <label class="form-label-custom" for="kategori">Kategori</label>
                        <select name="kategori"
                                id="kategori"
                                class="form-select input-rounded"
                                required>
                            <option value="">Pilih Kategori</option>
                            @if($kategoriValue && !in_array(strtoupper($kategoriValue), $kategoriOptions))
                                <option value="{{ $kategoriValue }}" selected>{{ $kategoriValue }}</option>
                            @endif
                            @foreach($kategoriOptions as $kategori)
                                <option value="{{ $kategori }}" {{ strtoupper((string)$kategoriValue) === $kategori ? 'selected' : '' }}>
                                    {{ $kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Jumlah Stok + Satuan --}}
                    <div class="mb-4">
                        <label class="form-label-custom" for="total_stok">Jumlah Stok & Satuan</label>

                        <div class="d-flex align-items-center gap-2">
                            <input type="number"
                                   name="total_stok"
                                   id="total_stok"
                                   class="form-control input-rounded"
                                   value="{{ old('total_stok', $stokBahan->total_stok) }}"
                                   min="0"
                                   required>

                            <select name="satuan"
                                    class="form-select input-rounded"
                                    style="width: 140px;"
                                    required>
                                <option value="gram" {{ old('satuan', $stokBahan->satuan) === 'gram' ? 'selected' : '' }}>Gram</option>
                                <option value="kg" {{ old('satuan', $stokBahan->satuan) === 'kg' ? 'selected' : '' }}>Kg</option>
                                <option value="ml" {{ old('satuan', $stokBahan->satuan) === 'ml' ? 'selected' : '' }}>ML</option>
                                <option value="liter" {{ old('satuan', $stokBahan->satuan) === 'liter' ? 'selected' : '' }}>Liter</option>
                                <option value="pcs" {{ old('satuan', $stokBahan->satuan) === 'pcs' ? 'selected' : '' }}>PCS</option>
                                <option value="pack" {{ old('satuan', $stokBahan->satuan) === 'pack' ? 'selected' : '' }}>Pack</option>
                                <option value="box" {{ old('satuan', $stokBahan->satuan) === 'box' ? 'selected' : '' }}>Box</option>
                            </select>
                        </div>
                    </div>

                    {{-- Batas Minimum --}}
                    <div class="mb-4">
                        <label class="form-label-custom" for="stok_minimum">Batas Minimum Stok</label>
                        <input type="number"
                               name="stok_minimum"
                               id="stok_minimum"
                               class="form-control input-rounded"
                               value="{{ old('stok_minimum', $stokBahan->stok_minimum) }}"
                               min="0"
                               required>
                        <small class="text-muted ms-2">
                            Sistem akan memberi peringatan jika stok ≤ nilai ini
                        </small>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn-submit">Update</button>
                        <a href="{{ route('stok.index') }}"
                           class="btn-cancel text-decoration-none d-inline-flex align-items-center justify-content-center">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="success-overlay" id="successOverlay">
                <div class="success-modal">
                    <div class="success-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div class="success-title">
                        Stok Berhasil Diupdate
                    </div>
                    <button type="button" class="btn-success-ok" id="successOkBtn">
                        OKE
                    </button>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const okBtn = document.getElementById('successOkBtn');
                    const redirect = () => window.location.href = "{{ route('stok.index') }}";
                    okBtn?.addEventListener('click', redirect);
                    setTimeout(redirect, 3000);
                });
            </script>
        @endif

    </div>
@endsection
