@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Data Barang</h1>
        <p class="text-muted mb-0">Kelola stok dan harga barang toko</p>
    </div>
    <a href="{{ route('barang.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Barang
    </a>
</div>

<div class="card page-card">
    <div class="card-body">
        <form method="GET" action="{{ route('barang.index') }}" class="row g-2 mb-3">
            <div class="col-md-6">
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Cari nama atau kode barang...">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary">Cari</button>
            </div>
            @if ($search !== '')
                <div class="col-auto">
                    <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            @endif
        </form>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($barangs as $barang)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $barang->kode_barang }}</td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>{{ formatRupiah($barang->harga) }}</td>
                            <td>
                                @if ($barang->stok <= 5)
                                    <span class="badge text-bg-warning">{{ $barang->stok }}</span>
                                @else
                                    {{ $barang->stok }}
                                @endif
                            </td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" onsubmit="return confirm('Hapus barang {{ $barang->nama_barang }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Data barang tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
