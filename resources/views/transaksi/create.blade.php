@extends('layouts.app')

@section('title', 'Transaksi Baru')

@section('content')
<div class="mb-4">
    <h1 class="h3 mb-1">Transaksi Baru</h1>
    <p class="text-muted mb-0">Tambahkan barang ke keranjang lalu simpan transaksi</p>
</div>

<div class="card page-card mb-3">
    <div class="card-header bg-white">
        <strong>Header Transaksi</strong>
    </div>
    <div class="card-body row g-3">
        <div class="col-md-6">
            <label class="form-label">Nomor Transaksi</label>
            <input type="text" class="form-control" value="{{ $nomorTransaksi }}" readonly>
        </div>
        <div class="col-md-6">
            <label class="form-label">Tanggal</label>
            <input type="text" class="form-control" value="{{ now()->format('d-m-Y') }}" readonly>
        </div>
    </div>
</div>

<div class="card page-card mb-3">
    <div class="card-header bg-white">
        <strong>Tambah Item</strong>
    </div>
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label for="barangSelect" class="form-label">Barang</label>
                <select id="barangSelect" class="form-select">
                    <option value="">-- Pilih Barang --</option>
                    @foreach ($barangs as $barang)
                        <option
                            value="{{ $barang->id }}"
                            data-nama="{{ $barang->nama_barang }}"
                            data-harga="{{ $barang->harga }}"
                            data-stok="{{ $barang->stok }}"
                        >
                            {{ $barang->kode_barang }} - {{ $barang->nama_barang }} ({{ formatRupiah($barang->harga) }}) — stok {{ $barang->stok }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Harga</label>
                <input type="text" id="hargaDisplay" class="form-control" value="-" readonly>
            </div>
            <div class="col-md-2">
                <label for="jumlahInput" class="form-label">Jumlah</label>
                <input type="number" id="jumlahInput" class="form-control" min="1" value="1">
            </div>
            <div class="col-md-2">
                <button type="button" id="btnTambah" class="btn btn-primary w-100">Tambah ke Keranjang</button>
            </div>
        </div>
        <p id="itemError" class="text-danger small mt-2 mb-0 d-none"></p>
    </div>
</div>

<form action="{{ route('transaksi.store') }}" method="POST" id="formTransaksi">
    @csrf
    <input type="hidden" name="items" id="itemsJson" value="{{ old('items') ? json_encode(old('items')) : '[]' }}">

    <div class="card page-card mb-3">
        <div class="card-header bg-white">
            <strong>Keranjang</strong>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle" id="cartTable">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="cartBody">
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total</th>
                            <th id="cartTotal">Rp 0</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-success btn-lg">
        <i class="bi bi-save"></i> Simpan Transaksi
    </button>
    <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary btn-lg">Kembali</a>
</form>
@endsection

@section('scripts')
<script>
    const formatRupiah = (amount) => {
        const n = Number(amount) || 0;
        return 'Rp ' + n.toLocaleString('id-ID');
    };

    const barangSelect = document.getElementById('barangSelect');
    const hargaDisplay = document.getElementById('hargaDisplay');
    const jumlahInput = document.getElementById('jumlahInput');
    const btnTambah = document.getElementById('btnTambah');
    const cartBody = document.getElementById('cartBody');
    const cartTotal = document.getElementById('cartTotal');
    const itemsJson = document.getElementById('itemsJson');
    const itemError = document.getElementById('itemError');
    const formTransaksi = document.getElementById('formTransaksi');

    let cart = [];

    try {
        const oldItems = JSON.parse(itemsJson.value || '[]');
        if (Array.isArray(oldItems) && oldItems.length) {
            cart = oldItems.map((item) => {
                const option = barangSelect.querySelector(`option[value="${item.barang_id}"]`);
                return {
                    barang_id: Number(item.barang_id),
                    nama: option ? option.dataset.nama : 'Barang',
                    harga: option ? Number(option.dataset.harga) : 0,
                    stok: option ? Number(option.dataset.stok) : 0,
                    jumlah: Number(item.jumlah),
                };
            });
        }
    } catch (e) {
        cart = [];
    }

    function showItemError(message) {
        itemError.textContent = message;
        itemError.classList.remove('d-none');
    }

    function hideItemError() {
        itemError.classList.add('d-none');
        itemError.textContent = '';
    }

    function selectedBarang() {
        const option = barangSelect.options[barangSelect.selectedIndex];
        if (!option || !option.value) {
            return null;
        }

        return {
            barang_id: Number(option.value),
            nama: option.dataset.nama,
            harga: Number(option.dataset.harga),
            stok: Number(option.dataset.stok),
        };
    }

    function serializeCart() {
        itemsJson.value = JSON.stringify(cart.map((item) => ({
            barang_id: item.barang_id,
            jumlah: item.jumlah,
        })));
    }

    function renderCart() {
        cartBody.innerHTML = '';
        let total = 0;

        if (!cart.length) {
            cartBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">Keranjang masih kosong.</td></tr>';
            cartTotal.textContent = formatRupiah(0);
            serializeCart();
            return;
        }

        cart.forEach((item, index) => {
            const subtotal = item.harga * item.jumlah;
            total += subtotal;
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.nama}</td>
                <td>${formatRupiah(item.harga)}</td>
                <td>${item.jumlah}</td>
                <td>${formatRupiah(subtotal)}</td>
                <td><button type="button" class="btn btn-sm btn-outline-danger" data-index="${index}">Hapus</button></td>
            `;
            cartBody.appendChild(row);
        });

        cartTotal.textContent = formatRupiah(total);
        serializeCart();
    }

    barangSelect.addEventListener('change', () => {
        const barang = selectedBarang();
        hargaDisplay.value = barang ? formatRupiah(barang.harga) : '-';
        hideItemError();
    });

    btnTambah.addEventListener('click', () => {
        hideItemError();
        const barang = selectedBarang();
        const jumlah = Number(jumlahInput.value);

        if (!barang) {
            showItemError('Pilih barang terlebih dahulu.');
            return;
        }

        if (!Number.isInteger(jumlah) || jumlah < 1) {
            showItemError('Jumlah minimal 1.');
            return;
        }

        const existing = cart.find((item) => item.barang_id === barang.barang_id);
        const newQty = existing ? existing.jumlah + jumlah : jumlah;

        if (newQty > barang.stok) {
            showItemError(`Stok ${barang.nama} tidak mencukupi. Sisa stok: ${barang.stok}.`);
            return;
        }

        if (existing) {
            existing.jumlah = newQty;
        } else {
            cart.push({
                barang_id: barang.barang_id,
                nama: barang.nama,
                harga: barang.harga,
                stok: barang.stok,
                jumlah: jumlah,
            });
        }

        jumlahInput.value = 1;
        renderCart();
    });

    cartBody.addEventListener('click', (event) => {
        const button = event.target.closest('button[data-index]');
        if (!button) {
            return;
        }
        cart.splice(Number(button.dataset.index), 1);
        renderCart();
    });

    formTransaksi.addEventListener('submit', (event) => {
        serializeCart();
        if (!cart.length) {
            event.preventDefault();
            showItemError('Keranjang masih kosong. Tambahkan minimal satu barang.');
        }
    });

    renderCart();
</script>
@endsection
