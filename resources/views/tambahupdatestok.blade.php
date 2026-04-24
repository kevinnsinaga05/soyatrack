@extends('layouts.app')

@section('title', 'Update Penjualan - GreSOY')

@section('content')
<div class="w-100 p-3 p-md-4">

    <div class="mb-4">
        <span class="fs-5 fw-semibold">Admin &gt; Update Produk &gt; Update Penjualan</span>
    </div>

    <div class="panel-card">
        <div class="panel-body p-0">

            <div class="px-4 py-3 text-white fw-bold"
                 style="background:#00c853;border-radius:18px 18px 0 0;">
                UPDATE PENJUALAN
            </div>

            @if(session('error'))
                <div class="alert alert-danger m-4">
                    <div>{{ session('error') }}</div>
                    @if(str_contains(strtolower(session('error')), 'stok'))
                        <hr class="my-2">
                        <div class="small">
                            Silakan tambah stok dulu di menu
                            <a href="{{ route('stok.masuk.create') }}" class="fw-semibold">Stok Masuk</a>,
                            lalu kirim ulang update penjualan.
                        </div>
                    @endif
                </div>
            @endif

            <form action="{{ route('update.stok.store') }}" method="POST" class="p-4">
                @csrf

                {{-- tanggal --}}
                <div class="mb-4">
                    <div class="d-flex flex-column flex-md-row gap-3 align-items-center justify-content-between"
                         style="border:1px solid #e5e7eb;border-radius:18px;padding:16px 20px;">
                        <div class="fw-semibold" style="min-width:120px;">Tanggal</div>
                        <input type="date" name="tanggal"
                               class="form-control rounded-pill px-4"
                               style="height:44px;max-width:320px;"
                               value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    </div>
                </div>

                <div id="salesContainer">
                    <div class="sales-row mb-3">
                        <div class="d-flex flex-column flex-md-row gap-3 justify-content-between align-items-center"
                             style="border:1px solid #e5e7eb;border-radius:18px;padding:16px 20px;">

                            {{-- Produk --}}
                            <div class="w-100">
                                <select name="product_id[]" class="form-select rounded-pill px-4"
                                        style="height:44px;" required>
                                    <option value="">Pilih Produk</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama_produk }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Gula --}}
                            <div class="w-100">
                                <select name="gula_id[]" class="form-select rounded-pill px-4"
                                        style="height:44px;" required>
                                    <option value="">Pilih Gula</option>
                                    @foreach($gulas as $g)
                                        <option value="{{ $g->id }}">{{ $g->nama_bahan }} ({{ strtoupper($g->satuan) }})</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Jumlah --}}
                            <div class="w-100">
                                <input type="number" name="jumlah_produk[]" class="form-control rounded-pill px-4"
                                       style="height:44px;" placeholder="Jumlah Terjual (Cup)" min="1" max="10000" required>
                            </div>

                            <button type="button"
                                    class="btn btn-danger rounded-pill px-3 d-none btn-remove"
                                    style="height:44px;min-width:44px;">✕</button>
                        </div>
                    </div>
                </div>

                <div class="text-center my-4">
                    <button type="button" id="btnAddSale"
                            class="btn fw-bold rounded-pill px-4"
                            style="background:#00c853;color:#000;">
                        + Tambah Penjualan
                    </button>
                </div>

                <div class="d-flex justify-content-center gap-3 mt-5">
                    <button type="submit"
                            class="btn rounded-pill px-5 fw-semibold"
                            style="background:#00c853;color:#fff;min-width:140px;">
                        Submit
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('salesContainer');
    const btnAdd = document.getElementById('btnAddSale');

    function attachRemove(btn){
        btn.addEventListener('click', () => btn.closest('.sales-row').remove());
    }

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
