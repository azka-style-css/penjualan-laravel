@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
<header class="page-head">
    <div>
        <h1 class="page-title">Data Barang</h1>
        <p class="page-sub">Kelola stok dan harga barang toko</p>
    </div>
    <a href="{{ route('barang.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah Barang
    </a>
</header>

<form method="GET" action="{{ route('barang.index') }}" class="mb-5 flex flex-wrap items-end gap-2" role="search">
    <div class="w-full sm:w-auto sm:min-w-72">
        <label for="search" class="label">Cari barang</label>
        <input type="search" id="search" name="search" value="{{ $search }}" class="input"
               placeholder="Nama atau kode barang…">
    </div>
    <button type="submit" class="btn btn-outline">
        <i class="bi bi-search" aria-hidden="true"></i> Cari
    </button>
    @if (filled($search))
        <a href="{{ route('barang.index') }}" class="btn btn-outline">Reset</a>
    @endif
</form>

<div class="table-wrap">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Kode Barang</th>
                <th scope="col">Nama Barang</th>
                <th scope="col" class="col-num">Harga</th>
                <th scope="col">Stok</th>
                <th scope="col" class="col-act">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($barangs as $barang)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="code">{{ $barang->kode_barang }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td class="col-num">{{ formatRupiah($barang->harga) }}</td>
                    <td>
                        @if ($barang->stok <= 5)
                            <span class="badge badge-warn">{{ $barang->stok }}</span>
                        @else
                            {{ $barang->stok }}
                        @endif
                    </td>
                    <td class="col-act">
                        <div class="cell-actions">
                            <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-sm btn-outline">Ubah</a>
                            <form action="{{ route('barang.destroy', $barang->id) }}" method="POST"
                                  data-confirm-title="Hapus barang"
                                  data-confirm="Barang “{{ $barang->nama_barang }}” akan dihapus permanen dan tidak bisa dikembalikan."
                                  data-confirm-action="Ya, hapus">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty-cell">Data barang tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
