@extends('layouts.app')
@section('title', 'Edit Penjualan - GreSOY')

@section('content')
<div class="w-100 p-3 p-md-4">

    <div class="mb-4">
        <span class="fs-5 fw-semibold">Admin &gt; Update Produk &gt; Edit Penjualan</span>
    </div>

    <style>
        /* Popup sukses */
        .success-overlay{
            position:fixed;
            inset:0;
            background:rgba(0,0,0,0.25);
            display:flex;
            align-items:center;
            justify-content:center;
            z-index:2000;
        }
        .success-modal{
            background:#fff;
            border-radius:28px;
            padding:32px 40px;
            max-width:640px;
            width:90%;
            box-shadow:0 24px 60px rgba(0,0,0,0.25);
            text-align:center;
        }
        .success-icon{
            font-size:72px;
            color:#00c853;
            margin-bottom:12px;
        }
        .success-title{
            font-size:26px;
            font-weight:700;
            margin-bottom:20px;
            color:#111827;
        }
        .btn-success-ok{
            background:#00c853;
            color:#fff;
            border-radius:999px;
            padding:10px 40px;
            font-weight:600;
            border:none;
        }
        .btn-success-ok:hover{
            background:#00b44b;
            color:#fff;
        }
    </style>

    <div class="panel-card">
        <div class="panel-body p-0">

            <div class="px-4 py-3 text-white fw-bold"
                 style="background:#00c853;border-radius:18px 18px 0 0;">
                EDIT PENJUALAN
            </div>

            @if(session('error'))
                <div class="alert alert-danger m-4">
                    <div>{{ session('error') }}</div>
                    @if(str_contains(strtolower(session('error')), 'stok'))
                        <hr class="my-2">
                        <div class="small">
                            Silakan tambah stok dulu di menu
                            <a href="{{ route('stok.masuk.create') }}" class="fw-semibold">Stok Masuk</a>,
                            lalu simpan ulang perubahan penjualan.
                        </div>
                    @endif
                </div>
            @endif

            <form action="{{ route('update.stok.update', $tanggal) }}" method="POST" class="p-4">
                @csrf
                @method('PUT')

                {{-- tanggal --}}
                <div class="mb-4">
                    <div class="d-flex flex-column flex-md-row gap-3 align-items-center justify-content-between"
                         style="border:1px solid #e5e7eb;border-radius:18px;padding:16px 20px;">
                        <div class="fw-semibold" style="min-width:120px;">Tanggal</div>
                        <input type="date" class="form-control rounded-pill px-4"
                               style="height:44px;max-width:320px;"
                               value="{{ $tanggal }}" disabled>
                    </div>
                </div>

                <div id="salesContainer">

                    @foreach($updates as $index => $u)
                    <div class="sales-row mb-3">
                        <div class="d-flex flex-column flex-md-row gap-3 justify-content-between align-items-center"
                             style="border:1px solid #e5e7eb;border-radius:18px;padding:16px 20px;">

                            {{-- Produk --}}
                            <div class="w-100">
                                <select name="product_id[]" class="form-select rounded-pill px-4"
                                        style="height:44px;" required>
                                    <option value="">Pilih Produk</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}"
                                            {{ $u->product_id == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama_produk }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Gula --}}
                            <div class="w-100">
                                <select name="gula_id[]" class="form-select rounded-pill px-4"
                                        style="height:44px;" required>
                                    <option value="">Pilih Gula</option>
                                    @foreach($gulas as $g)
                                        <option value="{{ $g->id }}"
                                            {{ $u->gula_id == $g->id ? 'selected' : '' }}>
                                            {{ $g->nama_bahan }} ({{ strtoupper($g->satuan) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Jumlah --}}
                            <div class="w-100">
                                <input type="number" name="jumlah_produk[]" class="form-control rounded-pill px-4"
                                       style="height:44px;"
                                       value="{{ $u->jumlah_produk }}"
                                       placeholder="Jumlah Terjual (Cup)" min="1" max="10000" required>
                            </div>

                            {{-- Remove --}}
                            <button type="button"
                                    class="btn btn-danger rounded-pill px-3 {{ $index == 0 ? 'd-none' : '' }} btn-remove"
                                    style="height:44px;min-width:44px;">
                                ✕
                            </button>
                        </div>
                    </div>
                    @endforeach

                </div>

                <div class="text-center my-4">
                    <button type="button"
                            id="btnAddSale"
                            class="btn fw-bold rounded-pill px-4"
                            style="background:#00c853;color:#000;">
                        + Tambah Penjualan
                    </button>
                </div>

                <div class="d-flex justify-content-center gap-3 mt-5">
                    <button type="submit"
                            class="btn rounded-pill px-5 fw-semibold"
                            style="background:#00c853;color:#fff;min-width:140px;">
                        Simpan
                    </button>

                    <a href="{{ route('update.stok') }}"
                       class="btn rounded-pill px-5 fw-semibold"
                       style="background:#ff3b30;color:#fff;min-width:140px;">
                        Cancel
                    </a>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- ✅ POPUP SUKSES --}}
@if(session('success'))
<div class="success-overlay" id="successOverlay">
    <div class="success-modal">
        <div class="success-icon">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div class="success-title">
            {{ session('success') }}
        </div>
        <button type="button" class="btn-success-ok" id="successOkBtn">
            OKE
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const okBtn = document.getElementById('successOkBtn');
    const redirect = () => window.location.href = "{{ route('update.stok') }}";

    okBtn?.addEventListener('click', redirect);

    // auto redirect setelah 2.5 detik
    setTimeout(redirect, 2500);
});
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('salesContainer');
    const btnAdd = document.getElementById('btnAddSale');

    function attachRemove(btn){
        btn.addEventListener('click', () => btn.closest('.sales-row').remove());
    }

    document.querySelectorAll('.btn-remove').forEach(btn => {
        if(!btn.classList.contains('d-none')) attachRemove(btn);
    });

    btnAdd.addEventListener('click', function () {
        const firstRow = container.querySelector('.sales-row');
        const newRow = firstRow.cloneNode(true);

        newRow.querySelectorAll('select, input').forEach(el => el.value = '');

        const rm = newRow.querySelector('.btn-remove');
        rm.classList.remove('d-none');
        attachRemove(rm);

        container.appendChild(newRow);
    });
});
</script>
@endsection
