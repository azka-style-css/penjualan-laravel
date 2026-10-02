@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content')
<header class="page-head">
    <div>
        <h1 class="page-title">Transaksi</h1>
        <p class="page-sub">Riwayat penjualan toko</p>
    </div>
    <a href="{{ route('transaksi.create') }}" class="btn btn-primary">
        <i class="bi bi-cart-plus" aria-hidden="true"></i> Transaksi Baru
    </a>
</header>

<form method="GET" action="{{ route('transaksi.index') }}" class="mb-5 flex flex-wrap items-end gap-3">
    <div class="w-full sm:w-44">
        <label for="tanggal_awal" class="label">Tanggal Awal</label>
        <input type="date" id="tanggal_awal" name="tanggal_awal" value="{{ $tanggalAwal }}" class="input">
    </div>
    <div class="w-full sm:w-44">
        <label for="tanggal_akhir" class="label">Tanggal Akhir</label>
        <input type="date" id="tanggal_akhir" name="tanggal_akhir" value="{{ $tanggalAkhir }}" class="input">
    </div>
    <div class="flex gap-2">
        <button type="submit" class="btn btn-outline">
            <i class="bi bi-funnel" aria-hidden="true"></i> Filter
        </button>
        @if (filled($tanggalAwal) || filled($tanggalAkhir))
            <a href="{{ route('transaksi.index') }}" class="btn btn-outline">Reset</a>
        @endif
    </div>
</form>

<div class="table-wrap">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Nomor Transaksi</th>
                <th scope="col">Tanggal</th>
                <th scope="col" class="col-num">Total</th>
                <th scope="col" class="col-act">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksis as $transaksi)
                <tr>
                    <td>{{ $transaksis->firstItem() + $loop->index }}</td>
                    <td class="code">{{ $transaksi->nomor_transaksi }}</td>
                    <td>{{ $transaksi->tanggal->format('d-m-Y') }}</td>
                    <td class="col-num">{{ formatRupiah($transaksi->total) }}</td>
                    <td class="col-act">
                        <a href="{{ route('transaksi.show', $transaksi->id) }}" class="btn btn-sm btn-outline">Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty-cell">Belum ada transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $transaksis->links('components.pagination') }}
@endsection
