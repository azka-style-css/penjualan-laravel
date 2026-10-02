@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<header class="page-head">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-sub">Ringkasan penjualan Toko RPL Jaya</p>
    </div>
    <a href="{{ route('transaksi.create') }}" class="btn btn-primary no-print">
        <i class="bi bi-cart-plus" aria-hidden="true"></i> Transaksi Baru
    </a>
</header>

<section class="stats-strip" aria-label="Statistik toko">
    <div class="stat-cell">
        <p class="stat-label">Total Penjualan</p>
        <p class="stat-value-lg" data-countup="{{ (int) $totalPenjualan }}" data-prefix="Rp ">{{ formatRupiah($totalPenjualan) }}</p>
    </div>
    <div class="stat-cell">
        <p class="stat-label">Total Barang</p>
        <p class="stat-value" data-countup="{{ (int) $totalBarang }}">{{ number_format((int) $totalBarang, 0, ',', '.') }}</p>
    </div>
    <div class="stat-cell">
        <p class="stat-label">Total Transaksi</p>
        <p class="stat-value" data-countup="{{ (int) $totalTransaksi }}">{{ number_format((int) $totalTransaksi, 0, ',', '.') }}</p>
    </div>
</section>

<section aria-labelledby="stokMenipis">
    <div class="section-head">
        <h2 class="section-title" id="stokMenipis">Peringatan Stok Menipis</h2>
        <span class="badge badge-warn">stok ≤ 5</span>
    </div>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Kode Barang</th>
                    <th scope="col">Nama Barang</th>
                    <th scope="col" class="col-num">Harga</th>
                    <th scope="col">Stok</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($lowStockBarangs as $barang)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="code">{{ $barang->kode_barang }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td class="col-num">{{ formatRupiah($barang->harga) }}</td>
                        <td><span class="badge badge-danger">{{ $barang->stok }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-cell">Tidak ada barang dengan stok menipis.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
