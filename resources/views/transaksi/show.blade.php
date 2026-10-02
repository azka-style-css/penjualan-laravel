@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<header class="page-head no-print">
    <div>
        <h1 class="page-title">Detail Transaksi</h1>
        <p class="page-sub code">{{ $transaksi->nomor_transaksi }}</p>
    </div>
    <div class="flex gap-2">
        <button type="button" class="btn btn-primary" onclick="window.print()">
            <i class="bi bi-printer" aria-hidden="true"></i> Cetak
        </button>
        <a href="{{ route('transaksi.index') }}" class="btn btn-outline">Kembali</a>
    </div>
</header>

<div class="receipt">
    <p class="receipt-store">Toko RPL Jaya</p>
    <p class="receipt-sub">Struk Penjualan</p>

    <div class="receipt-rule" role="presentation"></div>

    <div class="receipt-meta">
        <div class="flex justify-between gap-3">
            <span>No. Transaksi</span>
            <span>{{ $transaksi->nomor_transaksi }}</span>
        </div>
        <div class="flex justify-between gap-3">
            <span>Tanggal</span>
            <span>{{ $transaksi->tanggal->format('d-m-Y') }}</span>
        </div>
    </div>

    <div class="receipt-rule" role="presentation"></div>

    <table class="receipt-table">
        <thead>
            <tr>
                <th scope="col">Item</th>
                <th scope="col" class="r">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi->detailTransaksis as $detail)
                <tr>
                    <td>
                        {{ $detail->barang->nama_barang ?? '-' }}<br>
                        <span class="text-muted">{{ formatRupiah($detail->harga) }} × {{ $detail->jumlah }}</span>
                    </td>
                    <td class="r">{{ formatRupiah($detail->subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="receipt-rule" role="presentation"></div>

    <div class="receipt-total">
        <span>Total</span>
        <span>{{ formatRupiah($grandTotal) }}</span>
    </div>

    <div class="receipt-rule" role="presentation"></div>

    <p class="receipt-foot">Terima kasih telah berbelanja di Toko RPL Jaya</p>
</div>
@endsection
