{{-- SIDEBAR DESKTOP --}}
<aside class="sidebar-wrapper d-none d-md-flex flex-column">

    {{-- Brand Logo --}}
    <div class="sidebar-brand d-flex align-items-center">
        <img src="{{ asset('images/gressoy-logo.png') }}"
             alt="GreSOY"
             style="height:70px; width:auto; object-fit:contain;">
    </div>

    {{-- MENU --}}
    <div class="sidebar-section-title">Menu</div>
    <ul class="sidebar-menu">

        <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="sidebar-link">
                <span class="sidebar-icon"><i class="bi bi-grid-fill"></i></span>
                Dashboard
            </a>
        </li>

        <li class="sidebar-item {{ request()->routeIs('produk.*') ? 'active' : '' }}">
            <a href="{{ route('produk.index') }}" class="sidebar-link">
                <span class="sidebar-icon"><i class="bi bi-box-seam"></i></span>
                Produk
            </a>
        </li>

        <li class="sidebar-item {{ request()->routeIs('stok.*') ? 'active' : '' }}">
            <a href="{{ route('stok.index') }}" class="sidebar-link">
                <span class="sidebar-icon"><i class="bi bi-archive-fill"></i></span>
                Stok Bahan
            </a>
        </li>

        <li class="sidebar-item {{ request()->routeIs('update.stok*') ? 'active' : '' }}">
            <a href="{{ route('update.stok') }}" class="sidebar-link">
                <span class="sidebar-icon"><i class="bi bi-arrow-repeat"></i></span>
                Update Stok
            </a>
        </li>

        {{-- ✅ MENU BARU (LENGKAP) --}}
        <li class="sidebar-item {{ request()->routeIs('stok.masuk.*') ? 'active' : '' }}">
            <a href="{{ route('stok.masuk.index') }}" class="sidebar-link">
                <span class="sidebar-icon"><i class="bi bi-truck"></i></span>
                Stok Masuk
            </a>
        </li>

        <li class="sidebar-item {{ request()->routeIs('stok.adjust.*') ? 'active' : '' }}">
            <a href="{{ route('stok.adjust.index') }}" class="sidebar-link">
                <span class="sidebar-icon"><i class="bi bi-sliders"></i></span>
                Penyesuaian Stok
            </a>
        </li>

        <li class="sidebar-item {{ request()->routeIs('stok.opname.*') ? 'active' : '' }}">
            <a href="{{ route('stok.opname.create') }}" class="sidebar-link">
                <span class="sidebar-icon"><i class="bi bi-clipboard-check"></i></span>
                Stok Opname
            </a>
        </li>

        <li class="sidebar-item {{ request()->routeIs('stok.riwayat') ? 'active' : '' }}">
            <a href="{{ route('stok.riwayat') }}" class="sidebar-link">
                <span class="sidebar-icon"><i class="bi bi-clock-history"></i></span>
                Riwayat Stok
            </a>
        </li>

        {{-- ✅ LAPORAN LABA RUGI --}}
        <li class="sidebar-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <a href="{{ route('laporan.index') }}" class="sidebar-link">
                <span class="sidebar-icon"><i class="bi bi-receipt"></i></span>
                Laporan Laba Rugi
            </a>
        </li>

    </ul>

    {{-- LOGOUT --}}
    <div class="sidebar-footer mt-auto">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-logout-sneat">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </button>
        </form>
    </div>

</aside>


{{-- ✅ SIDEBAR MOBILE (OFFCANVAS) --}}
<div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="sidebarOffcanvas">
    <div class="offcanvas-header">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/gressoy-logo.png') }}" alt="GreSOY" style="height:50px;">
        </div>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column">

        <div class="sidebar-section-title">Menu</div>
        <ul class="sidebar-menu">

            <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="sidebar-link" data-bs-dismiss="offcanvas">
                    <span class="sidebar-icon"><i class="bi bi-grid-fill"></i></span>
                    Dashboard
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('produk.*') ? 'active' : '' }}">
                <a href="{{ route('produk.index') }}" class="sidebar-link" data-bs-dismiss="offcanvas">
                    <span class="sidebar-icon"><i class="bi bi-box-seam"></i></span>
                    Produk
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('stok.*') ? 'active' : '' }}">
                <a href="{{ route('stok.index') }}" class="sidebar-link" data-bs-dismiss="offcanvas">
                    <span class="sidebar-icon"><i class="bi bi-archive-fill"></i></span>
                    Stok Bahan
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('update.stok*') ? 'active' : '' }}">
                <a href="{{ route('update.stok') }}" class="sidebar-link" data-bs-dismiss="offcanvas">
                    <span class="sidebar-icon"><i class="bi bi-arrow-repeat"></i></span>
                    Update Penjualan
                </a>
            </li>

            {{-- ✅ MENU BARU (MOBILE JUGA LENGKAP) --}}
            <li class="sidebar-item {{ request()->routeIs('stok.masuk.*') ? 'active' : '' }}">
                <a href="{{ route('stok.masuk.index') }}" class="sidebar-link" data-bs-dismiss="offcanvas">
                    <span class="sidebar-icon"><i class="bi bi-truck"></i></span>
                    Stok Masuk
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('stok.adjust.*') ? 'active' : '' }}">
                <a href="{{ route('stok.adjust.index') }}" class="sidebar-link" data-bs-dismiss="offcanvas">
                    <span class="sidebar-icon"><i class="bi bi-sliders"></i></span>
                    Penyesuaian Stok
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('stok.opname.*') ? 'active' : '' }}">
                <a href="{{ route('stok.opname.create') }}" class="sidebar-link" data-bs-dismiss="offcanvas">
                    <span class="sidebar-icon"><i class="bi bi-clipboard-check"></i></span>
                    Stok Opname
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('stok.riwayat') ? 'active' : '' }}">
                <a href="{{ route('stok.riwayat') }}" class="sidebar-link" data-bs-dismiss="offcanvas">
                    <span class="sidebar-icon"><i class="bi bi-clock-history"></i></span>
                    Riwayat Stok
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <a href="{{ route('laporan.index') }}" class="sidebar-link" data-bs-dismiss="offcanvas">
                    <span class="sidebar-icon"><i class="bi bi-receipt"></i></span>
                    Laporan Laba Rugi
                </a>
            </li>

        </ul>

        {{-- LOGOUT MOBILE --}}
        <div class="sidebar-footer mt-auto">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-logout-sneat w-100">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </button>
            </form>
        </div>

    </div>
</div>


{{-- ✅ Fix hover numpuk (tanpa merusak style asli) --}}
<style>
    .sidebar-item.active .sidebar-link:hover {
        background: inherit !important;
    }
</style>
