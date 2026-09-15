<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') — Toko RPL Jaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --brand: #0f766e;
            --brand-dark: #115e59;
            --sidebar-width: 250px;
        }
        body { background-color: #f4f6f8; min-height: 100vh; }
        .navbar-brand { font-weight: 700; letter-spacing: .02em; }
        .app-sidebar {
            width: var(--sidebar-width);
            min-height: calc(100vh - 56px);
            background: #0f172a;
        }
        .app-sidebar .nav-link {
            color: #cbd5e1;
            border-radius: .5rem;
            margin-bottom: .25rem;
        }
        .app-sidebar .nav-link:hover,
        .app-sidebar .nav-link.active {
            background: var(--brand);
            color: #fff;
        }
        .app-main { min-height: calc(100vh - 56px); }
        .stat-card { border: 0; box-shadow: 0 0.25rem 0.75rem rgba(15, 23, 42, .06); }
        .page-card { border: 0; box-shadow: 0 0.25rem 0.75rem rgba(15, 23, 42, .06); }
        @media print {
            .no-print, .navbar, .app-sidebar { display: none !important; }
            .app-main { width: 100% !important; max-width: 100% !important; }
            body { background: #fff; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark no-print" style="background:#0f766e;">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-shop me-1"></i> Toko RPL Jaya
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}" href="{{ route('barang.index') }}">Data Barang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('transaksi.*') ? 'active' : '' }}" href="{{ route('transaksi.index') }}">Transaksi</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="d-flex">
        <aside class="app-sidebar p-3 d-none d-md-block no-print">
            <p class="text-uppercase small text-secondary mb-2">Menu</p>
            <nav class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}" href="{{ route('barang.index') }}">
                    <i class="bi bi-box-seam me-2"></i> Data Barang
                </a>
                <a class="nav-link {{ request()->routeIs('transaksi.index') || request()->routeIs('transaksi.show') ? 'active' : '' }}" href="{{ route('transaksi.index') }}">
                    <i class="bi bi-receipt me-2"></i> Transaksi
                </a>
                <a class="nav-link {{ request()->routeIs('transaksi.create') ? 'active' : '' }}" href="{{ route('transaksi.create') }}">
                    <i class="bi bi-cart-plus me-2"></i> Transaksi Baru
                </a>
            </nav>
        </aside>

        <main class="app-main flex-grow-1 p-3 p-md-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Terjadi kesalahan validasi.</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
