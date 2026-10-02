@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
<header class="page-head">
    <div>
        <h1 class="page-title">Tambah Barang</h1>
        <p class="page-sub">Masukkan data barang baru</p>
    </div>
</header>

<form action="{{ route('barang.store') }}" method="POST" data-guard>
    @csrf
    <p class="hint-text mb-4">Kolom bertanda <span class="req" aria-hidden="true">*</span> wajib diisi.</p>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="kode_barang" class="label">Kode Barang <span class="req" aria-hidden="true">*</span></label>
            <input type="text" name="kode_barang" id="kode_barang" value="{{ old('kode_barang') }}"
                   class="input code @error('kode_barang') input-error @enderror" maxlength="20"
                   required aria-required="true"
                   @error('kode_barang') aria-invalid="true" aria-describedby="err-kode" @enderror>
            @error('kode_barang')
                <p class="error-text" id="err-kode">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="nama_barang" class="label">Nama Barang <span class="req" aria-hidden="true">*</span></label>
            <input type="text" name="nama_barang" id="nama_barang" value="{{ old('nama_barang') }}"
                   class="input @error('nama_barang') input-error @enderror" maxlength="100"
                   required aria-required="true"
                   @error('nama_barang') aria-invalid="true" aria-describedby="err-nama" @enderror>
            @error('nama_barang')
                <p class="error-text" id="err-nama">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="harga" class="label">Harga <span class="req" aria-hidden="true">*</span></label>
            <div class="input-group">
                <span class="input-addon">Rp</span>
                <input type="number" name="harga" id="harga" value="{{ old('harga') }}"
                       class="input @error('harga') input-error @enderror" min="0" step="1"
                       required aria-required="true" inputmode="numeric"
                       @error('harga') aria-invalid="true" aria-describedby="err-harga" @enderror>
            </div>
            @error('harga')
                <p class="error-text" id="err-harga">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="stok" class="label">Stok <span class="req" aria-hidden="true">*</span></label>
            <input type="number" name="stok" id="stok" value="{{ old('stok') }}"
                   class="input @error('stok') input-error @enderror" min="0" step="1"
                   required aria-required="true" inputmode="numeric"
                   @error('stok') aria-invalid="true" aria-describedby="err-stok" @enderror>
            @error('stok')
                <p class="error-text" id="err-stok">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary" data-guard-btn data-loading-label="Menyimpan…">
            <i class="bi bi-save" aria-hidden="true"></i> Simpan
        </button>
        <a href="{{ route('barang.index') }}" class="btn btn-outline">Batal</a>
    </div>
</form>
@endsection
