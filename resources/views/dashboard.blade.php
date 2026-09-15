@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Dashboard</h1>
        <p class="text-muted mb-0">Ringkasan penjualan Toko RPL Jaya</p>
    </div>
    <a href="{{ route('transaksi.create') }}" class="btn btn-success">
        <i class="bi bi-cart-plus"></i> Transaksi Baru
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Total Barang</p>
                        <h2 class="mb-0">{{ $totalBarang }}</h2>
                    </div>
                    <div class="fs-1 text-primary"><i class="bi bi-box-seam"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Total Transaksi</p>
                        <h2 class="mb-0">{{ $totalTransaksi }}</h2>
                    </div>
                    <div class="fs-1 text-info"><i class="bi bi-receipt"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Total Penjualan</p>
                        <h2 class="mb-0">{{ formatRupiah($totalPenjualan) }}</h2>
                    </div>
                    <div class="fs-1 text-success"><i class="bi bi-cash-stack"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card page-card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h2 class="h5 mb-0"><i class="bi bi-exclamation-triangle text-warning me-2"></i>Peringatan Stok Menipis</h2>
        <span class="badge text-bg-warning">stok &le; 5</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lowStockBarangs as $barang)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $barang->kode_barang }}</td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>{{ formatRupiah($barang->harga) }}</td>
                            <td>
                                <span class="badge text-bg-danger">{{ $barang->stok }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Tidak ada barang dengan stok menipis.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
