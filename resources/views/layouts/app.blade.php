<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'GRESSOY Dashboard')</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f5f5f5;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            margin: 0;
            overflow: hidden; /* ✅ biar yang scroll hanya main */
        }

        /* ✅ WRAPPER FULL SCREEN */
        .app-wrapper {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* ===== Sidebar Hijau GreSOY ===== */
        .sidebar-wrapper {
            width: 260px;
            background: #ffffff;
            border-right: 1px solid #c6f1d0;
            border-radius: 0 24px 24px 0;
            padding: 1.5rem 1.25rem;
            box-shadow: 4px 0 15px rgba(0, 168, 45, 0.10);

            /* ✅ sidebar tidak ikut scroll */
            height: 100vh;
            position: sticky;
            top: 0;
            overflow: hidden;
            flex-shrink: 0;
        }

        /* Logo Kotak */
        .brand-logo {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: #00a82d;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.1rem;
            margin-right: 10px;
        }

        /* Nama brand */
        .brand-text {
            font-weight: 700;
            font-size: 1.1rem;
            color: #035b19;
        }

        .sidebar-section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #7da889;
            margin: 0.75rem 0 0.25rem;
        }

        /* Menu list */
        .sidebar-menu {
            margin: 0;
            padding-left: 0;
            list-style: none;
        }

        .sidebar-item {
            margin-bottom: 0.25rem;
        }

        /* Default link */
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.55rem 0.75rem;
            border-radius: 12px;
            text-decoration: none;
            color: #0c4a1e;
            font-size: 0.94rem;
            transition: all 0.15s ease;
        }

        /* Icon wrapper */
        .sidebar-icon {
            width: 26px;
            height: 26px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.6rem;
            background: #d6ffe1;
            color: #00a82d;
            font-size: 1rem;
        }

        /* Active state */
        .sidebar-item.active .sidebar-link {
            background: #d6ffe1;
            font-weight: 600;
            color: #00a82d;
        }

        .sidebar-item.active .sidebar-icon {
            background: #00a82d;
            color: #fff;
        }

        /* Hover */
        .sidebar-link:hover {
            background: #e9ffe6;
            color: #00a82d;
        }

        .sidebar-link:hover .sidebar-icon {
            background: #00a82d;
            color: #fff;
        }

        /* Footer Logout */
        .sidebar-footer {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid #c6f1d0;
        }

        .btn-logout-sneat {
            width: 100%;
            border-radius: 999px;
            border: 1px solid #00a82d;
            color: #00a82d;
            background: #ffffff;
            font-weight: 500;
        }

        .btn-logout-sneat:hover {
            background: #00a82d;
            color: #ffffff;
        }

        /* ✅ MAIN CONTENT yang scroll */
        .main-content {
            flex-grow: 1;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Scrollbar halus */
        .main-content::-webkit-scrollbar {
            width: 10px;
        }
        .main-content::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.15);
            border-radius: 999px;
        }
        .main-content::-webkit-scrollbar-track {
            background: transparent;
        }

        /* ✅ Responsive: mobile sidebar pakai offcanvas */
        @media (max-width: 768px) {
            body {
                overflow: auto;
            }
            .app-wrapper {
                flex-direction: column;
                height: auto;
            }
            .sidebar-wrapper {
                display: none;
            }
            .main-content {
                height: auto;
                overflow: visible;
            }
        }

        /* === THEME DASHBOARD BARU ala modern card === */
        .dashboard-summary { margin-bottom: 24px; }

        .summary-card {
            border-radius: 18px;
            padding: 18px 22px;
            color: #1f2933;
            background: #ffffff;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
            border: 1px solid rgba(148, 163, 184, 0.25);
            position: relative;
            overflow: hidden;
        }
        .summary-card::before {
            content: "";
            position: absolute;
            inset: -40%;
            background: radial-gradient(circle at top right, rgba(255,255,255,0.9), transparent 55%);
            opacity: 0.8;
        }
        .summary-card-inner { position: relative; z-index: 1; }
        .summary-label {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
        }
        .summary-main {
            font-size: 30px;
            font-weight: 700;
            color: #0f172a;
        }
        .summary-sub {
            font-size: 13px;
            margin-top: 4px;
            color: #16a34a;
        }

        .summary-card.total-produk { background: linear-gradient(135deg, #e0ffe9, #ffffff); }
        .summary-card.total-penjualan { background: linear-gradient(135deg, #e0f4ff, #ffffff); }
        .summary-card.stok-hampir-habis { background: linear-gradient(135deg, #ffe7e0, #ffffff); }

        .panel-card {
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.06);
            border: 1px solid rgba(148, 163, 184, 0.15);
            overflow: hidden;
        }
        .panel-header {
            padding: 16px 20px 10px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.9);
        }
        .panel-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }
        .panel-subtitle {
            font-size: 13px;
            color: #6b7280;
        }
        .panel-body {
            padding: 6px 18px 16px;
        }
        .stok-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 4px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }
        .stok-row:last-child { border-bottom: none; }
        .stok-badge-pill {
            font-size: 12px;
            font-weight: 600;
            border-radius: 999px;
            padding: 6px 16px;
            background-color: #fee2e2;
            color: #b91c1c;
        }
    </style>
</head>

<body>

{{-- NAVBAR MOBILE --}}
<nav class="navbar navbar-light mobile-navbar-fixed d-md-none">
    <div class="container-fluid">
        <button class="btn btn-outline-secondary" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas"
                aria-controls="sidebarOffcanvas">
            ☰
        </button>
        <span class="navbar-brand mb-0 h1">Gressoy Admin</span>
    </div>
</nav>

{{-- ✅ Spacer supaya konten tidak ketutup --}}
<div class="mobile-navbar-space"></div>

{{-- ✅ OFFCANVAS SIDEBAR MOBILE --}}
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
    <div class="offcanvas-header">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column p-3">

        {{-- ✅ Brand --}}
        <div class="d-flex align-items-center mb-4">
            <img src="{{ asset('images/gressoy-logo.png') }}"
                 alt="GreSOY"
                 style="height:70px; width:auto; object-fit:contain;">
        </div>

        <div class="sidebar-section-title">Menu</div>

        {{-- ✅ MENU MOBILE FULL --}}
        <ul class="sidebar-menu mt-2">

            <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="sidebar-link mobile-link">
                    <span class="sidebar-icon"><i class="bi bi-grid-fill"></i></span>
                    Dashboard
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('produk.*') ? 'active' : '' }}">
                <a href="{{ route('produk.index') }}" class="sidebar-link mobile-link">
                    <span class="sidebar-icon"><i class="bi bi-box-seam"></i></span>
                    Produk
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('stok.*') ? 'active' : '' }}">
                <a href="{{ route('stok.index') }}" class="sidebar-link mobile-link">
                    <span class="sidebar-icon"><i class="bi bi-archive-fill"></i></span>
                    Stok Bahan
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('update.stok*') ? 'active' : '' }}">
                <a href="{{ route('update.stok') }}" class="sidebar-link mobile-link">
                    <span class="sidebar-icon"><i class="bi bi-arrow-repeat"></i></span>
                    Update Stok
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('stok.masuk.*') ? 'active' : '' }}">
                <a href="{{ route('stok.masuk.index') }}" class="sidebar-link mobile-link">
                    <span class="sidebar-icon"><i class="bi bi-truck"></i></span>
                    Stok Masuk
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('stok.adjust.*') ? 'active' : '' }}">
                <a href="{{ route('stok.adjust.index') }}" class="sidebar-link mobile-link">
                    <span class="sidebar-icon"><i class="bi bi-sliders"></i></span>
                    Penyesuaian Stok
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('stok.opname.*') ? 'active' : '' }}">
                <a href="{{ route('stok.opname.create') }}" class="sidebar-link mobile-link">
                    <span class="sidebar-icon"><i class="bi bi-clipboard-check"></i></span>
                    Stok Opname
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('stok.riwayat') ? 'active' : '' }}">
                <a href="{{ route('stok.riwayat') }}" class="sidebar-link mobile-link">
                    <span class="sidebar-icon"><i class="bi bi-clock-history"></i></span>
                    Riwayat Stok
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <a href="{{ route('laporan.index') }}" class="sidebar-link mobile-link">
                    <span class="sidebar-icon"><i class="bi bi-receipt"></i></span>
                    Laporan Laba Rugi
                </a>
            </li>

        </ul>

        {{-- ✅ FOOTER (Sticky di bawah) --}}
        <div class="sidebar-footer mt-auto pt-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-logout-sneat w-100">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </form>
        </div>

    </div>
</div>

{{-- ✅ FIX hover numpuk --}}
<style>
    .sidebar-item.active .sidebar-link:hover {
        background: #d6ffe1 !important;
        color: #00a82d !important;
    }
</style>

{{-- ✅ WRAPPER --}}
<div class="app-wrapper">

    {{-- SIDEBAR DESKTOP --}}
    @include('partials.sidebar')

    {{-- ✅ MAIN CONTENT --}}
    <main class="main-content">
        @yield('content')
    </main>

</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
