@extends('layouts.app')

@section('title', 'Barang Masuk - GreSOY')

@section('content')
<div class="w-100 p-3 p-md-4">

    <div class="mb-4">
        <span class="fs-5 fw-semibold">Admin &gt; Stok &gt; Barang Masuk</span>
    </div>

    <style>
        .success-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.25);
            display:flex; align-items:center; justify-content:center; z-index:2000;
        }
        .success-modal {
            background:#fff; border-radius: 28px; padding: 32px 40px;
            max-width: 640px; width: 90%;
            box-shadow: 0 24px 60px rgba(0,0,0,0.25); text-align:center;
        }
        .success-icon { font-size: 72px; color:#00c853; margin-bottom: 12px; }
        .success-title { font-size: 26px; font-weight: 700; margin-bottom: 20px; color:#111827; }
        .btn-success-ok { background:#00c853; color:#fff; border-radius: 999px; padding: 10px 40px; font-weight: 600; border: none; }

        /* Keterangan kecil */
        .hint-text {
            font-size: 12px;
            color: #6b7280;
            margin-top: 6px;
            padding-left: 8px;
        }
    </style>

    <div class="panel-card">
        <div class="panel-body p-0">
            <div class="px-4 py-3 text-white fw-bold"
                 style="background:#00c853;border-radius:18px 18px 0 0;">
                BARANG MASUK
            </div>

            @if(session('error'))
                <div class="alert alert-danger m-3">{{ session('error') }}</div>
            @endif

            <form action="{{ route('stok.masuk.store') }}" method="POST" class="p-4">
                @csrf

                <div class="mb-4">
                    <div class="d-flex flex-column flex-md-row gap-3 align-items-center justify-content-between"
                         style="border:1px solid #e5e7eb;border-radius:18px;padding:16px 20px;">
                        <div class="fw-semibold" style="min-width:120px;">Tanggal Masuk</div>

                        <input type="date" name="tanggal"
                               class="form-control rounded-pill px-4"
                               style="height:44px;max-width:320px;"
                               value="{{ old('tanggal', date('Y-m-d')) }}"
                               required>
                    </div>
                </div>

                <div id="rowsContainer">
                    <div class="row-item mb-3">
                        <div class="d-flex flex-column flex-md-row gap-3 justify-content-between align-items-start"
                             style="border:1px solid #e5e7eb;border-radius:18px;padding:16px 20px;">

                            {{-- PILIH BAHAN --}}
                            <div class="w-100">
                                <select name="stok_bahan_id[]" class="form-select rounded-pill px-4" style="height:44px;" required>
                                    <option value="">Pilih Bahan</option>
                                    @foreach($bahan as $b)
                                        <option value="{{ $b->id }}">{{ $b->nama_bahan }} ({{ strtoupper($b->satuan) }})</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- QTY --}}
                            <div class="w-100">
                                <input type="number" step="0.01" min="0.01"
                                       name="qty[]" class="form-control rounded-pill px-4"
                                       style="height:44px;" placeholder="Jumlah Masuk" required>
                            </div>

                            {{-- EXPIRED --}}
                            <div class="w-100">
                                <div class="d-flex align-items-center gap-2">
                                    <input type="date" name="expired_at[]"
                                           class="form-control rounded-pill px-4"
                                           style="height:44px;" placeholder="Expired (opsional)">
                                    <i class="bi bi-info-circle-fill text-muted"
                                       data-bs-toggle="tooltip"
                                       data-bs-placement="top"
                                       title="Tanggal ini adalah tanggal kadaluarsa/expired bahan (opsional). Isi jika bahan memiliki masa expired."></i>
                                </div>
                                <div class="hint-text">
                                    Tanggal expired / kadaluarsa bahan (opsional)
                                </div>
                            </div>

                            {{-- HARGA --}}
                            <div class="w-100">
                                <input type="number" step="0.01" min="0"
                                       name="harga_beli_per_satuan[]" class="form-control rounded-pill px-4"
                                       style="height:44px;" placeholder="Harga beli / satuan (opsional)">
                                <div class="hint-text">
                                    Isi jika ingin menghitung biaya & keuntungan
                                </div>
                            </div>

                            {{-- REMOVE --}}
                            <button type="button"
                                    class="btn btn-danger rounded-pill px-3 d-none btn-remove"
                                    style="height:44px;min-width:44px;">✕</button>
                        </div>
                    </div>
                </div>

                <div class="text-center my-4">
                    <button type="button" id="btnAdd"
                            class="btn fw-bold rounded-pill px-4"
                            style="background:#00c853;color:#000;">
                        + Tambah Baris
                    </button>
                </div>

                <div class="d-flex justify-content-center gap-3 mt-4">
                    <button type="submit" class="btn rounded-pill px-5 fw-semibold"
                            style="background:#00c853;color:#fff;min-width:140px;">
                        Submit
                    </button>

                    <a href="{{ route('stok.masuk.index') }}" class="btn rounded-pill px-5 fw-semibold"
                       style="background:#ff3b30;color:#fff;min-width:140px;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- POPUP sukses --}}
    @if(session('success'))
        <div class="success-overlay" id="successOverlay">
            <div class="success-modal">
                <div class="success-icon"><i class="bi bi-check-circle-fill"></i></div>
                <div class="success-title">Barang masuk berhasil disimpan</div>
                <button type="button" class="btn-success-ok" id="successOkBtn">OKE</button>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function(){
                const go = () => window.location.href = "{{ route('stok.masuk.index') }}";
                document.getElementById('successOkBtn')?.addEventListener('click', go);
                setTimeout(go, 2500);
            });
        </script>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const container = document.getElementById('rowsContainer');
    const btnAdd = document.getElementById('btnAdd');

    function attachRemove(btn){
        btn.addEventListener('click', () => btn.closest('.row-item').remove());
    }

    btnAdd.addEventListener('click', function(){
        const first = container.querySelector('.row-item');
        const clone = first.cloneNode(true);

        clone.querySelectorAll('select, input').forEach(el => el.value = '');

        const rm = clone.querySelector('.btn-remove');
        rm.classList.remove('d-none');
        attachRemove(rm);

        container.appendChild(clone);

        // refresh tooltip bootstrap
        const tooltips = [].slice.call(clone.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltips.map(el => new bootstrap.Tooltip(el));
    });

    // init tooltip pertama kali
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection
