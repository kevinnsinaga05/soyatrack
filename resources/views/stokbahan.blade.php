@extends('layouts.app')

@section('title', 'Stok Bahan - GreSOY')

@section('content')
<div class="w-100 p-3 p-md-4">

    <div class="mb-4">
        <span class="fs-5 fw-semibold">Admin &gt; Stok Bahan</span>
    </div>

    <style>
        /* ===== Modal Confirm ===== */
        .confirm-overlay, .success-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }
        .confirm-modal, .success-modal {
            background: #fff;
            border-radius: 28px;
            padding: 32px 40px;
            max-width: 640px;
            width: 90%;
            box-shadow: 0 24px 60px rgba(0,0,0,0.25);
            text-align: center;
        }
        .confirm-icon { font-size: 72px; color: #f97316; margin-bottom: 12px; }
        .success-icon { font-size: 72px; color: #00c853; margin-bottom: 12px; }
        .confirm-title, .success-title { font-size: 28px; font-weight: 700; margin-bottom: 24px; color:#111827; }
        .btn-confirm-yes, .btn-confirm-no, .btn-success-ok {
            border-radius: 999px; padding: 10px 40px; font-weight: 600; border: none;
        }
        .btn-confirm-yes { background:#ff3b30; color:#fff; }
        .btn-confirm-no { background:#e5e7eb; color:#111827; }
        .btn-success-ok { background:#00c853; color:#fff; }

        /* ===== Mobile Card ===== */
        .stok-card {
            border-radius: 16px;
            background: #ffffff;
            border: 1px solid rgba(226,232,240,0.9);
            box-shadow: 0 10px 25px rgba(15,23,42,0.06);
            padding: 14px 14px;
            margin-bottom: 12px;
        }
        .stok-card-title {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
        }
        .stok-card-sub {
            font-size: 13px;
            color: #6b7280;
        }

        .btn-action-mobile {
            border-radius: 999px;
            padding: 10px 0;
            font-weight: 600;
            border: none;
            width: 100%;
        }

        @media (max-width: 576px) {
            .panel-body {
                padding: 12px !important;
            }
        }
    </style>

    {{-- Header hijau --}}
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center"
             style="border-radius:18px;background:#00c853;box-shadow:0 18px 40px rgba(15,23,42,0.10);padding:14px 20px;">
            <div class="fw-bold text-white">DAFTAR STOK</div>

            <a href="{{ route('stok.create') }}"
               class="btn btn-light fw-semibold rounded-pill px-4 py-1">
                Tambah Stok
            </a>
        </div>
    </div>

    {{-- Panel utama --}}
    <div class="panel-card mt-3">
        <div class="panel-body">

            {{-- Header kolom (desktop) --}}
            <div class="d-none d-md-flex px-2 pb-2 border-bottom">
            <div class="flex-grow-1 fw-semibold">Nama Bahan</div>

            {{-- Header Sisa Stok --}}
            <div class="fw-semibold me-3" style="width:240px; padding-left:2px;">
                Sisa Stok
            </div>

            <div style="width:190px;"></div>
        </div>

            @forelse($stokBahans as $stok)

                {{-- DESKTOP/TABLET --}}
                <div class="d-none d-md-flex align-items-center justify-content-between py-3 px-2 border-bottom">

                    {{-- Nama --}}
                    <div class="fw-semibold text-dark flex-grow-1">
                        {{ $stok->nama_bahan }}
                    </div>

                    {{-- Sisa stok --}}
                    <div class="me-3" style="width:240px;">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="fw-semibold">
                                {{ $stok->total_stok }} {{ strtoupper($stok->satuan) }}
                            </span>
                        </div>
                    </div>

                    {{-- Aksi --}}
                    <div class="d-flex gap-2" style="width:190px; justify-content:flex-end;">
                        <button type="button"
                                class="btn btn-sm fw-semibold rounded-pill btn-delete"
                                style="background-color:#ff3b30;color:#fff;min-width:80px;"
                                data-id="{{ $stok->id }}"
                                data-nama="{{ $stok->nama_bahan }}">
                            Hapus
                        </button>

                        <a href="{{ route('stok.edit', $stok->id) }}"
                           class="btn btn-sm fw-semibold rounded-pill"
                           style="background-color:#0ad41c;color:#fff;min-width:80px;">
                            Edit
                        </a>
                    </div>
                </div>

                {{-- MOBILE CARD --}}
                <div class="d-md-none stok-card">
                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <div class="stok-card-title">{{ $stok->nama_bahan }}</div>
                            <div class="stok-card-sub">
                                Sisa: <span class="fw-semibold">{{ $stok->total_stok }} {{ strtoupper($stok->satuan) }}</span>
                            </div>
                        </div>

                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="button"
                                class="btn-action-mobile btn-delete"
                                style="background:#ff3b30;color:#fff;"
                                data-id="{{ $stok->id }}"
                                data-nama="{{ $stok->nama_bahan }}">
                            Hapus
                        </button>

                        <a href="{{ route('stok.edit', $stok->id) }}"
                           class="btn-action-mobile text-decoration-none text-center"
                           style="background:#0ad41c;color:#fff;">
                            Edit
                        </a>
                    </div>
                </div>

            @empty
                <div class="text-center py-4 text-muted">
                    Belum ada stok bahan.
                </div>
            @endforelse

        </div>
    </div>

    {{-- form delete hidden --}}
    <form id="deleteForm" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    {{-- modal confirm delete --}}
    <div class="confirm-overlay d-none" id="confirmOverlay">
        <div class="confirm-modal">
            <div class="confirm-icon"><i class="bi bi-question-circle-fill"></i></div>
            <div class="confirm-title">
                Apakah Anda yakin ingin menghapus<br>
                <span id="confirmName"></span>?
            </div>
            <div class="d-flex justify-content-center gap-3">
                <button type="button" class="btn-confirm-no" id="btnNo">Tidak</button>
                <button type="button" class="btn-confirm-yes" id="btnYes">Ya, Hapus</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btns = document.querySelectorAll('.btn-delete');
            const overlay = document.getElementById('confirmOverlay');
            const confirmName = document.getElementById('confirmName');
            const btnNo = document.getElementById('btnNo');
            const btnYes = document.getElementById('btnYes');
            const deleteForm = document.getElementById('deleteForm');
            const urlTemplate = "{{ route('stok.destroy', ':id') }}";
            let deleteUrl = null;

            btns.forEach(b => {
                b.addEventListener('click', function () {
                    deleteUrl = urlTemplate.replace(':id', this.dataset.id);
                    confirmName.textContent = this.dataset.nama;
                    overlay.classList.remove('d-none');
                });
            });

            btnNo.addEventListener('click', () => overlay.classList.add('d-none'));
            btnYes.addEventListener('click', () => {
                if(deleteUrl){
                    deleteForm.action = deleteUrl;
                    deleteForm.submit();
                }
            });
        });
    </script>

</div>
@endsection
