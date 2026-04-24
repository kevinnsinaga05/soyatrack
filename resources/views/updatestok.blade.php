@extends('layouts.app')

@section('title', 'Update Stok - GreSOY')

@section('content')
<div class="w-100 p-3 p-md-4">

    <div class="mb-4">
        <span class="fs-5 fw-semibold">Admin &gt; Update Stok</span>
    </div>

    <style>
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
        .btn-success-ok:hover { background:#00b44b; color:#fff; }
    </style>

    {{-- POPUP SUKSES (untuk delete / update / tambah) --}}
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
            document.addEventListener('DOMContentLoaded', function () {
                const okBtn = document.getElementById('successOkBtn');
                const overlay = document.getElementById('successOverlay');

                if(okBtn && overlay){
                    okBtn.addEventListener('click', () => overlay.remove());

                    // auto close 3 detik
                    setTimeout(() => overlay.remove(), 3000);
                }
            });
        </script>
    @endif

    {{-- Header hijau --}}
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center"
             style="border-radius:18px;background:#00c853;box-shadow:0 18px 40px rgba(15,23,42,0.10);padding:14px 20px;">
            <div class="fw-bold text-white">UPDATE STOK</div>

            <a href="{{ route('update.stok.create') }}"
               class="btn btn-light fw-semibold rounded-pill px-4 py-1">
                Update Stok
            </a>
        </div>
    </div>

    {{-- Panel --}}
    <div class="panel-card mt-3">
        <div class="panel-body">

            {{-- Header kolom --}}
            <div class="d-none d-md-flex px-2 pb-2 border-bottom">
                <div class="flex-grow-1 fw-semibold">Tanggal</div>
                <div class="fw-semibold me-3 text-center" style="width:180px;">Produk Terjual</div>
                <div style="width:360px;"></div>
            </div>

            @forelse($updates as $u)

                {{-- DESKTOP/TABLET --}}
                <div class="d-none d-md-flex align-items-center justify-content-between py-3 px-2 border-bottom">
                    <div class="fw-semibold text-dark flex-grow-1">
                        {{ \Carbon\Carbon::parse($u->tanggal)->translatedFormat('d F Y') }}
                    </div>

                    <div class="me-3 text-center" style="width:180px;">
                        <span class="fw-semibold">{{ $u->total_terjual }}</span>
                    </div>

                    <div class="d-flex gap-2 flex-wrap" style="width:360px; justify-content:flex-end;">
                        <a href="{{ route('update.stok.show', $u->tanggal) }}"
                           class="btn btn-sm rounded-pill"
                           style="background:#2563eb;color:#fff;min-width:40px;"
                           title="Lihat">
                            <i class="bi bi-eye-fill"></i>
                        </a>



                        <button type="button"
                                class="btn btn-sm fw-semibold rounded-pill btn-delete"
                                style="background-color:#ff3b30;color:#fff;min-width:80px;"
                                data-tanggal="{{ $u->tanggal }}"
                                data-label="{{ \Carbon\Carbon::parse($u->tanggal)->translatedFormat('d F Y') }}">
                            Hapus
                        </button>

                        <a href="{{ route('update.stok.edit', $u->tanggal) }}"
                           class="btn btn-sm fw-semibold rounded-pill"
                           style="background-color:#0ad41c;color:#fff;min-width:80px;">
                            Edit
                        </a>
                    </div>
                </div>

                {{-- MOBILE --}}
                <div class="d-md-none py-3 px-2 border-bottom">
                    <div class="fw-semibold text-dark mb-2">
                        {{ \Carbon\Carbon::parse($u->tanggal)->translatedFormat('d F Y') }}
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Produk Terjual</span>
                        <span class="fw-semibold">{{ $u->total_terjual }}</span>
                    </div>

                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('update.stok.show', $u->tanggal) }}"
                           class="btn btn-sm rounded-pill flex-fill"
                           style="background:#2563eb;color:#fff;"
                           title="Lihat">
                            <i class="bi bi-eye-fill"></i>
                        </a>


                        <button type="button"
                                class="btn btn-sm fw-semibold rounded-pill flex-fill btn-delete"
                                style="background-color:#ff3b30;color:#fff;"
                                data-tanggal="{{ $u->tanggal }}"
                                data-label="{{ \Carbon\Carbon::parse($u->tanggal)->translatedFormat('d F Y') }}">
                            Hapus
                        </button>

                        <a href="{{ route('update.stok.edit', $u->tanggal) }}"
                           class="btn btn-sm fw-semibold rounded-pill flex-fill"
                           style="background-color:#0ad41c;color:#fff;">
                            Edit
                        </a>
                    </div>
                </div>

            @empty
                <div class="text-center py-4 text-muted">
                    Belum ada update penjualan.
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
                penjualan tanggal <span id="confirmName"></span>?
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

            const urlTemplate = "{{ route('update.stok.delete', ':tanggal') }}";
            let deleteUrl = null;

            btns.forEach(b => {
                b.addEventListener('click', function () {
                    deleteUrl = urlTemplate.replace(':tanggal', this.dataset.tanggal);
                    confirmName.textContent = this.dataset.label;
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
