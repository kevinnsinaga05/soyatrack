@extends('layouts.app')

@section('title', 'Tambah Stok - GreSOY')

@section('content')
<div class="w-100 p-3 p-md-4">

    <div class="mb-4">
        <span class="fs-5 fw-semibold">Admin &gt; Stok Bahan &gt; Tambah Stok</span>
    </div>

    <style>
        .form-label-custom { font-weight: 600; font-size: 14px; color: #111827; }
        .input-rounded { border-radius: 999px; border: 1.5px solid #111; padding: 10px 16px; font-size: 14px; }
        .btn-submit { background:#00c853; color:#fff; border-radius: 12px; padding: 10px 32px; font-weight: 600; border: none; }
        .btn-cancel { background:#ff3b30; color:#fff; border-radius: 12px; padding: 10px 32px; font-weight: 600; border: none; }

        .btn-submit:hover { background:#00b44b; }
        .btn-cancel:hover { background:#e13127; }

        .success-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.25); display:flex; align-items:center; justify-content:center; z-index:2000; }
        .success-modal { background:#fff; border-radius: 28px; padding: 32px 40px; max-width: 640px; width: 90%; box-shadow: 0 24px 60px rgba(0,0,0,0.25); text-align:center; }
        .success-icon { font-size: 72px; color:#00c853; margin-bottom: 12px; }
        .success-title { font-size: 28px; font-weight: 700; margin-bottom: 24px; color:#111827; }
        .btn-success-ok { background:#00c853; color:#fff; border-radius: 999px; padding: 10px 40px; font-weight: 600; border: none; }
        .btn-success-ok:hover { background:#00b44b; }

        /* Toggle checkbox agar lebih rapi */
        .form-check-input:checked {
            background-color: #00c853;
            border-color: #00c853;
        }
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

            <form action="{{ route('stok.store') }}" method="POST" autocomplete="off">
                @csrf

                {{-- Nama Bahan --}}
                <div class="mb-3">
                    <label class="form-label-custom">Nama Bahan</label>
                    <input type="text"
                           name="nama_bahan"
                           class="form-control input-rounded"
                           value="{{ old('nama_bahan') }}"
                           placeholder="Contoh: Kremer, Cup Large, Botol 250ml"
                           required>
                </div>

                {{-- Kategori --}}
                <div class="mb-3">
                    <label class="form-label-custom">Kategori</label>
                    <select name="kategori"
                            class="form-select input-rounded"
                            required>
                        <option value="">Pilih Kategori</option>
                        <option value="Powder" {{ old('kategori')=='Powder'?'selected':'' }}>Powder</option>
                        <option value="Bahan Baku" {{ old('kategori')=='Bahan Baku'?'selected':'' }}>Bahan Baku</option>
                        <option value="Packaging" {{ old('kategori')=='Packaging'?'selected':'' }}>Packaging</option>
                        <option value="Gula" {{ old('kategori')=='Gula'?'selected':'' }}>Gula</option>
                        <option value="Perlengkapan" {{ old('kategori')=='Perlengkapan'?'selected':'' }}>Perlengkapan</option>
                        <option value="Kebersihan" {{ old('kategori')=='Kebersihan'?'selected':'' }}>Kebersihan</option>
                        <option value="Lainnya" {{ old('kategori')=='Lainnya'?'selected':'' }}>Lainnya</option>
                    </select>
                </div>

                {{-- Jumlah Stok & Satuan --}}
                <div class="mb-4">
                    <label class="form-label-custom">Jumlah Stok & Satuan</label>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <input type="number"
                               name="total_stok"
                               class="form-control input-rounded flex-grow-1"
                               value="{{ old('total_stok') }}"
                               min="0"
                               step="0.01"
                               required>

                        <select name="satuan"
                                class="form-select input-rounded"
                                style="width: 160px;"
                                required>
                            <option value="">Satuan</option>
                            <option value="gram" {{ old('satuan')=='gram'?'selected':'' }}>Gram</option>
                            <option value="kg" {{ old('satuan')=='kg'?'selected':'' }}>Kg</option>
                            <option value="ml" {{ old('satuan')=='ml'?'selected':'' }}>ML</option>
                            <option value="liter" {{ old('satuan')=='liter'?'selected':'' }}>Liter</option>
                            <option value="pcs" {{ old('satuan')=='pcs'?'selected':'' }}>PCS</option>
                            <option value="pack" {{ old('satuan')=='pack'?'selected':'' }}>Pack</option>
                            <option value="box" {{ old('satuan')=='box'?'selected':'' }}>Box</option>
                            <option value="sachet" {{ old('satuan')=='sachet'?'selected':'' }}>Sachet</option>
                            <option value="bottle" {{ old('satuan')=='bottle'?'selected':'' }}>Bottle</option>
                        </select>
                    </div>
                </div>

                {{-- Batas Minimum --}}
                <div class="mb-4">
                    <label class="form-label-custom">Batas Minimum Stok</label>
                    <input type="number"
                           name="stok_minimum"
                           class="form-control input-rounded"
                           value="{{ old('stok_minimum', 0) }}"
                           min="0"
                           step="0.01"
                           required>
                    <small class="text-muted ms-2">
                        Sistem akan memberi peringatan jika stok ≤ nilai ini
                    </small>
                </div>

                {{-- Harga beli per satuan --}}
                <div class="mb-4">
                    <label class="form-label-custom">Harga Beli per Satuan (Opsional)</label>
                    <input type="number"
                           name="harga_beli_per_satuan"
                           class="form-control input-rounded"
                           value="{{ old('harga_beli_per_satuan') }}"
                           min="0"
                           step="0.01"
                           placeholder="Contoh: 15000">
                    <small class="text-muted ms-2">
                        Digunakan untuk perhitungan keuntungan/rugi
                    </small>
                </div>

                {{-- Track Expired --}}
                <div class="mb-4">
                    <div class="form-check d-flex align-items-center gap-2">
                        <input class="form-check-input"
                               type="checkbox"
                               name="track_expired"
                               id="track_expired"
                               value="1"
                               {{ old('track_expired') ? 'checked' : '' }}>
                        <label class="form-label-custom mb-0" for="track_expired">
                            Aktifkan Track Expired (Barang ini punya masa kadaluarsa)
                        </label>
                    </div>
                    <small class="text-muted ms-4">
                        Jika aktif, sistem akan meminta tanggal expired saat stok masuk
                    </small>
                </div>

                {{-- Tombol --}}
                <div class="d-flex gap-3 mt-4">
                    <button type="submit" class="btn-submit">
                        Submit
                    </button>

                    <a href="{{ route('stok.index') }}"
                       class="btn-cancel text-decoration-none d-inline-flex align-items-center justify-content-center">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- POPUP SUCCESS --}}
    @if (session('success'))
        <div class="success-overlay" id="successOverlay">
            <div class="success-modal">
                <div class="success-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="success-title">
                    Stok Berhasil Ditambahkan
                </div>
                <button type="button" class="btn-success-ok" id="successOkBtn">OKE</button>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const redirect = () => window.location.href = "{{ route('stok.index') }}";
                document.getElementById('successOkBtn')?.addEventListener('click', redirect);
                setTimeout(redirect, 3000);
            });
        </script>
    @endif

</div>
@endsection
