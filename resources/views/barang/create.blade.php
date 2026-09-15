@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
<div class="mb-4">
    <h1 class="h3 mb-1">Tambah Barang</h1>
    <p class="text-muted mb-0">Masukkan data barang baru</p>
</div>

<div class="card page-card">
    <div class="card-body">
        <form action="{{ route('barang.store') }}" method="POST" class="row g-3">
            @csrf
            <div class="col-md-6">
                <label for="kode_barang" class="form-label">Kode Barang</label>
                <input type="text" name="kode_barang" id="kode_barang" value="{{ old('kode_barang') }}" class="form-control @error('kode_barang') is-invalid @enderror" maxlength="20" required>
                @error('kode_barang')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="nama_barang" class="form-label">Nama Barang</label>
                <input type="text" name="nama_barang" id="nama_barang" value="{{ old('nama_barang') }}" class="form-control @error('nama_barang') is-invalid @enderror" maxlength="100" required>
                @error('nama_barang')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" name="harga" id="harga" value="{{ old('harga') }}" class="form-control @error('harga') is-invalid @enderror" min="0" step="1" required>
                @error('harga')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="stok" class="form-label">Stok</label>
                <input type="number" name="stok" id="stok" value="{{ old('stok') }}" class="form-control @error('stok') is-invalid @enderror" min="0" step="1" required>
                @error('stok')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
