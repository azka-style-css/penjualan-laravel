<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('title', 'Dashboard') — Kasir Simple · Toko RPL Jaya</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body>
    <a class="skip-link" href="#konten">Lewati ke konten</a>

    <div class="app-shell">
        <div id="backdrop" class="backdrop" aria-hidden="true"></div>

        <aside id="sidebar" class="sidebar no-print" aria-label="Navigasi utama">
            <div class="sidebar-brand">
                <span class="brand-tile"><i class="bi bi-shop" aria-hidden="true"></i></span>
                <span>
                    <span class="brand-name">Kasir Simple</span><br>
                    <span class="brand-sub">Toko RPL Jaya</span>
                </span>
            </div>

            <p class="sidebar-label">Menu</p>
            <nav class="grid gap-1">
                <a class="navlink {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   href="{{ route('dashboard') }}"
                   @if (request()->routeIs('dashboard')) aria-current="page" @endif>
                    <i class="bi bi-speedometer2" aria-hidden="true"></i> Dashboard
                </a>
                <a class="navlink {{ request()->routeIs('barang.*') ? 'active' : '' }}"
                   href="{{ route('barang.index') }}"
                   @if (request()->routeIs('barang.*')) aria-current="page" @endif>
                    <i class="bi bi-box-seam" aria-hidden="true"></i> Data Barang
                </a>
                <a class="navlink {{ request()->routeIs('transaksi.index') || request()->routeIs('transaksi.show') ? 'active' : '' }}"
                   href="{{ route('transaksi.index') }}"
                   @if (request()->routeIs('transaksi.index') || request()->routeIs('transaksi.show')) aria-current="page" @endif>
                    <i class="bi bi-receipt" aria-hidden="true"></i> Transaksi
                </a>
                <a class="navlink {{ request()->routeIs('transaksi.create') ? 'active' : '' }}"
                   href="{{ route('transaksi.create') }}"
                   @if (request()->routeIs('transaksi.create')) aria-current="page" @endif>
                    <i class="bi bi-cart-plus" aria-hidden="true"></i> Transaksi Baru
                </a>
            </nav>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="topbar no-print">
                <button type="button" class="icon-btn" data-drawer-toggle
                        aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="sidebar">
                    <i class="bi bi-list" aria-hidden="true"></i>
                </button>
                <span class="font-display text-sm font-semibold">Kasir Simple</span>
            </header>

            <main id="konten" class="content">
                @if (session('success'))
                    <div class="alert alert-success" role="status" data-alert>
                        <i class="bi bi-check-circle" aria-hidden="true"></i>
                        <div class="alert-body">{{ session('success') }}</div>
                        <button type="button" class="alert-close" data-dismiss aria-label="Tutup pesan">
                            <i class="bi bi-x-lg" aria-hidden="true"></i>
                        </button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger" role="alert" data-alert>
                        <i class="bi bi-exclamation-octagon" aria-hidden="true"></i>
                        <div class="alert-body">{{ session('error') }}</div>
                        <button type="button" class="alert-close" data-dismiss aria-label="Tutup pesan">
                            <i class="bi bi-x-lg" aria-hidden="true"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <dialog id="confirmDialog" class="confirm-dialog" aria-labelledby="confirmTitle">
        <form method="dialog" class="confirm-card">
            <h2 id="confirmTitle" class="confirm-title" data-confirm-title-el>Konfirmasi</h2>
            <p class="confirm-body" data-confirm-body-el></p>
            <div class="confirm-actions">
                <button value="cancel" class="btn btn-outline" formnovalidate>Batal</button>
                <button value="confirm" class="btn btn-danger-solid" data-confirm-action-el>Hapus</button>
            </div>
        </form>
    </dialog>

    @yield('scripts')
</body>
</html>
