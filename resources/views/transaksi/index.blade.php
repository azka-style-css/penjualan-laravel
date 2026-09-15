@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Transaksi</h1>
        <p class="text-muted mb-0">Riwayat penjualan toko</p>
    </div>
    <a href="{{ route('transaksi.create') }}" class="btn btn-success">
        <i class="bi bi-cart-plus"></i> Transaksi Baru
    </a>
</div>

<div class="card page-card">
    <div class="card-body">
        <form method="GET" action="{{ route('transaksi.index') }}" class="row g-2 align-items-end mb-3">
            <div class="col-md-3">
                <label class="form-label">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" value="{{ $tanggalAwal }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" value="{{ $tanggalAkhir }}" class="form-control">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary">Filter</button>
                <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Transaksi</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $transaksi)
                        <tr>
                            <td>{{ $transaksis->firstItem() + $loop->index }}</td>
                            <td>{{ $transaksi->nomor_transaksi }}</td>
                            <td>{{ $transaksi->tanggal->format('d-m-Y') }}</td>
                            <td>{{ formatRupiah($transaksi->total) }}</td>
                            <td>
                                <a href="{{ route('transaksi.show', $transaksi->id) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $transaksis->links() }}
        </div>
    </div>
</div>
@endsection
