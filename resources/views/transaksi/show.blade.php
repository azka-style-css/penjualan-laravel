@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('styles')
<style>
    @media print {
        .receipt-paper { box-shadow: none !important; }
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <div>
        <h1 class="h3 mb-1">Detail Transaksi</h1>
        <p class="text-muted mb-0">{{ $transaksi->nomor_transaksi }}</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-primary" onclick="window.print()">
            <i class="bi bi-printer"></i> Print
        </button>
        <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

<div class="card page-card receipt-paper">
    <div class="card-body">
        <div class="text-center mb-4">
            <h2 class="h4 mb-1">Toko RPL Jaya</h2>
            <p class="text-muted mb-0">Struk Penjualan</p>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <div><strong>Nomor Transaksi:</strong> {{ $transaksi->nomor_transaksi }}</div>
                <div><strong>Tanggal:</strong> {{ $transaksi->tanggal->format('d-m-Y') }}</div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksi->detailTransaksis as $detail)
                        <tr>
                            <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                            <td>{{ formatRupiah($detail->harga) }}</td>
                            <td>{{ $detail->jumlah }}</td>
                            <td>{{ formatRupiah($detail->subtotal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Grand Total</th>
                        <th>{{ formatRupiah($grandTotal) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <p class="text-center text-muted mb-0">Terima kasih telah berbelanja di Toko RPL Jaya.</p>
    </div>
</div>
@endsection
