@extends('layouts.app')

@section('title', 'Produk - GreSOY')

@section('content')
    <div class="w-100 p-3 p-md-4">

        {{-- Breadcrumb --}}
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <span class="fs-5 fw-semibold">Admin &gt; Produk</span>
        </div>

        {{-- CSS POPUP (pakai style yang sama dengan popup tambah produk) --}}
        <style>
            .success-overlay,
            .confirm-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.25);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 2000;
            }

            .success-modal,
            .confirm-modal {
                background: #ffffff;
                border-radius: 28px;
                padding: 32px 40px;
                max-width: 640px;
                width: 90%;
                box-shadow: 0 24px 60px rgba(0, 0, 0, 0.25);
                text-align: center;
            }

            .success-icon {
                font-size: 72px;
                color: #00c853;
                margin-bottom: 12px;
            }

            .confirm-icon {
                font-size: 72px;
                color: #f97316;
                margin-bottom: 12px;
            }

            .success-title,
            .confirm-title {
                font-size: 28px;
                font-weight: 700;
                margin-bottom: 24px;
                color: #111827;
            }

            .btn-success-ok,
            .btn-confirm-yes,
            .btn-confirm-no {
                border-radius: 999px;
                padding: 10px 40px;
                font-weight: 600;
                border: none;
            }

            .btn-success-ok {
                background-color: #00c853;
                color: #fff;
            }

            .btn-success-ok:hover {
                background-color: #00b44b;
                color: #fff;
            }

            .btn-confirm-yes {
                background-color: #ff3b30;
                color: #fff;
            }

            .btn-confirm-yes:hover {
                background-color: #e13127;
                color: #fff;
            }

            .btn-confirm-no {
                background-color: #e5e7eb;
                color: #111827;
            }

            .btn-confirm-no:hover {
                background-color: #d1d5db;
                color: #111827;
            }
        </style>

        {{-- HEADER DAFTAR PRODUK --}}
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center"
                 style="
                    border-radius: 18px;
                    background: linear-gradient(135deg, #19D88C, #0FA4E9);
                    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.10);
                    padding: 14px 20px;
                 ">
                <div class="fw-bold text-white">
                    DAFTAR PRODUK
                </div>

                <a href="{{ route('produk.create') }}"
                   class="btn btn-light fw-semibold rounded-pill px-4 py-1">
                    Tambah Produk
                </a>
            </div>
        </div>

        {{-- PESAN SUKSES (EDIT / TAMBAH / HAPUS) VIA ALERT BIASA (opsional) --}}
        @if (session('success') && !session('deleted'))
            <div class="alert alert-success mb-3">
                {{ session('success') }}
            </div>
        @endif

        {{-- DAFTAR PRODUK --}}
        @if($products->isEmpty())
            <div class="panel-card mt-3">
                <div class="panel-body text-center py-4">
                    <p class="mb-0 text-muted">Belum ada produk.</p>
                </div>
            </div>
        @else
            <div class="panel-card mt-3">

                <div class="panel-body">
                    @foreach($products as $product)
                        <div class="d-flex align-items-center justify-content-between py-3 px-2
                                    {{ !$loop->last ? 'border-bottom' : '' }}"
                             style="border-color: rgba(226,232,240,0.9);">

                            {{-- Nama Produk --}}
                            <div class="fw-semibold text-dark">
                                {{ $product->nama_produk }}
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="d-flex gap-2">

                                {{-- HAPUS (pakai popup custom) --}}
                                <button type="button"
                                        class="btn btn-sm fw-semibold rounded-pill btn-delete"
                                        style="background-color:#ff3b30;color:#fff;min-width:80px;"
                                        data-id="{{ $product->id }}"
                                        data-nama="{{ $product->nama_produk }}">
                                    Hapus
                                </button>

                                {{-- EDIT --}}
                                <a href="{{ route('produk.edit', $product->id) }}"
                                   class="btn btn-sm fw-semibold rounded-pill"
                                   style="background-color:#0ad41c;color:#fff;min-width:80px;">
                                    Edit
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        @endif

        {{-- FORM DELETE HIDDEN --}}
        <form id="deleteForm" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>

        {{-- POPUP KONFIRMASI HAPUS --}}
        <div class="confirm-overlay d-none" id="confirmOverlay">
            <div class="confirm-modal">
                <div class="confirm-icon">
                    <i class="bi bi-question-circle-fill"></i>
                </div>
                <div class="confirm-title">
                    Apakah Anda yakin ingin menghapus<br>
                    <span id="confirmProductName"></span>?
                </div>
                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn-confirm-no" id="btnCancelDelete">
                        Tidak
                    </button>
                    <button type="button" class="btn-confirm-yes" id="btnConfirmDelete">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>

        {{-- POPUP SUKSES HAPUS (setelah redirect) --}}
        @if (session('deleted'))
            <div class="success-overlay" id="deletedOverlay">
                <div class="success-modal">
                    <div class="success-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div class="success-title">
                        Produk Berhasil Dihapus
                    </div>
                    <button type="button" class="btn-success-ok" id="deletedOkBtn">
                        OKE
                    </button>
                </div>
            </div>
        @endif

        {{-- SCRIPT UNTUK HANDLE DELETE + POPUP --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const deleteButtons = document.querySelectorAll('.btn-delete');
                const confirmOverlay = document.getElementById('confirmOverlay');
                const confirmProductName = document.getElementById('confirmProductName');
                const btnCancelDelete = document.getElementById('btnCancelDelete');
                const btnConfirmDelete = document.getElementById('btnConfirmDelete');
                const deleteForm = document.getElementById('deleteForm');

                let currentDeleteUrl = null;

                // template URL route destroy: /produk/{id}
                const urlTemplate = "{{ route('produk.destroy', ':id') }}";

                deleteButtons.forEach(btn => {
                    btn.addEventListener('click', function () {
                        const id = this.getAttribute('data-id');
                        const nama = this.getAttribute('data-nama');

                        currentDeleteUrl = urlTemplate.replace(':id', id);
                        confirmProductName.textContent = nama;
                        confirmOverlay.classList.remove('d-none');
                    });
                });

                if (btnCancelDelete) {
                    btnCancelDelete.addEventListener('click', function () {
                        confirmOverlay.classList.add('d-none');
                        currentDeleteUrl = null;
                    });
                }

                if (btnConfirmDelete) {
                    btnConfirmDelete.addEventListener('click', function () {
                        if (currentDeleteUrl && deleteForm) {
                            deleteForm.setAttribute('action', currentDeleteUrl);
                            deleteForm.submit();
                        }
                    });
                }

                // popup sukses delete (setelah redirect)
                const deletedOverlay = document.getElementById('deletedOverlay');
                const deletedOkBtn = document.getElementById('deletedOkBtn');

                if (deletedOverlay && deletedOkBtn) {
                    deletedOkBtn.addEventListener('click', function () {
                        deletedOverlay.style.display = 'none';
                    });
                }
            });
        </script>
    </div>
@endsection
