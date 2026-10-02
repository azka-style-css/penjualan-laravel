@extends('layouts.app')

@section('title', 'Transaksi Baru')

@section('content')
<header class="page-head">
    <div>
        <h1 class="page-title">Transaksi Baru</h1>
        <p class="page-sub">Tambahkan barang ke keranjang, lalu simpan transaksi</p>
    </div>
</header>

<div class="pos-grid">
    <div class="pos-stack">
        <section class="pos-section" aria-labelledby="headTransaksi">
            <div class="section-head">
                <h2 class="section-title" id="headTransaksi">Header Transaksi</h2>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <p class="label">Nomor Transaksi</p>
                    <p class="code text-base font-semibold">{{ $nomorTransaksi }}</p>
                </div>
                <div>
                    <p class="label">Tanggal</p>
                    <p class="font-medium">{{ now()->format('d-m-Y') }}</p>
                </div>
            </div>
        </section>

        <section class="pos-section" aria-labelledby="tambahItem">
            <div class="section-head">
                <h2 class="section-title" id="tambahItem">Tambah Item</h2>
            </div>
            <div class="grid gap-3">
                <div>
                    <label for="barangSelect" class="label">Barang</label>
                    <select id="barangSelect" class="input">
                        <option value="">— Pilih barang —</option>
                        @foreach ($barangs as $barang)
                            <option
                                value="{{ $barang->id }}"
                                data-nama="{{ $barang->nama_barang }}"
                                data-harga="{{ $barang->harga }}"
                                data-stok="{{ $barang->stok }}"
                            >
                                {{ $barang->kode_barang }} — {{ $barang->nama_barang }} · {{ formatRupiah($barang->harga) }} · stok {{ $barang->stok }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="hargaDisplay" class="label">Harga</label>
                        <input type="text" id="hargaDisplay" class="input" value="—" readonly tabindex="-1" aria-label="Harga barang terpilih">
                    </div>
                    <div>
                        <label for="jumlahInput" class="label">Jumlah</label>
                        <input type="number" id="jumlahInput" class="input" min="1" value="1" inputmode="numeric">
                    </div>
                </div>
                <button type="button" id="btnTambah" class="btn btn-outline btn-block">
                    <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah ke Keranjang
                </button>
                <p id="itemError" class="error-text js-error" role="alert"></p>
            </div>
        </section>

        <section class="pos-section" aria-labelledby="keranjangBelanja">
            <div class="section-head">
                <h2 class="section-title" id="keranjangBelanja">Keranjang</h2>
                <span id="cartCount" class="badge badge-accent">0 item</span>
            </div>
            <div class="table-wrap">
                <table class="table" id="cartTable">
                    <thead>
                        <tr>
                            <th scope="col">Nama Barang</th>
                            <th scope="col" class="col-num">Harga</th>
                            <th scope="col">Jumlah</th>
                            <th scope="col" class="col-num">Subtotal</th>
                            <th scope="col" class="col-act"><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody id="cartBody"></tbody>
                </table>
            </div>
        </section>
    </div>

    <form action="{{ route('transaksi.store') }}" method="POST" id="formTransaksi" data-guard>
        @csrf
        <input type="hidden" name="items" id="itemsJson" value="{{ old('items') ? json_encode(old('items')) : '[]' }}">

        <section class="pos-summary" aria-labelledby="ringkasanBayar">
            <div class="section-head">
                <h2 class="section-title" id="ringkasanBayar">Pembayaran</h2>
            </div>

            <p class="summary-total-label">Total Belanja</p>
            <p class="summary-total" id="cartTotal" aria-live="polite">Rp 0</p>

            <div class="hr" role="presentation"></div>

            <div>
                <label for="bayarInput" class="label">Uang Bayar</label>
                <div class="input-group">
                    <span class="input-addon">Rp</span>
                    <input type="number" id="bayarInput" class="input" min="0" inputmode="numeric" placeholder="0">
                </div>
                <p class="hint-text">Opsional — hanya untuk menghitung kembalian, tidak ikut tersimpan.</p>
            </div>

            <div class="summary-row">
                <span class="label mb-0">Kembalian</span>
                <span id="kembalianDisplay" class="kembalian-ok" aria-live="polite">—</span>
            </div>

            <p id="submitError" class="error-text js-error" role="alert"></p>
            @error('items')
                <p class="error-text">{{ $message }}</p>
            @enderror

            <div class="mt-4 grid gap-2">
                <button type="submit" id="btnSimpan" class="btn btn-primary btn-lg btn-block"
                        data-guard-btn data-loading-label="Menyimpan…" disabled>
                    <i class="bi bi-save" aria-hidden="true"></i> Simpan Transaksi
                </button>
                <a href="{{ route('transaksi.index') }}" class="btn btn-outline btn-block">Batal</a>
            </div>
        </section>
    </form>
</div>
@endsection

@section('scripts')
<script>
    const formatRupiah = (amount) => 'Rp ' + (Number(amount) || 0).toLocaleString('id-ID');
    const esc = (value) => String(value).replace(/[&<>"']/g, (c) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
    }[c]));

    const barangSelect = document.getElementById('barangSelect');
    const hargaDisplay = document.getElementById('hargaDisplay');
    const jumlahInput = document.getElementById('jumlahInput');
    const btnTambah = document.getElementById('btnTambah');
    const cartBody = document.getElementById('cartBody');
    const cartTotalEl = document.getElementById('cartTotal');
    const cartCountEl = document.getElementById('cartCount');
    const itemsJson = document.getElementById('itemsJson');
    const itemError = document.getElementById('itemError');
    const submitError = document.getElementById('submitError');
    const formTransaksi = document.getElementById('formTransaksi');
    const bayarInput = document.getElementById('bayarInput');
    const kembalianDisplay = document.getElementById('kembalianDisplay');
    const btnSimpan = document.getElementById('btnSimpan');

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

    const cartTotal = () => cart.reduce((total, item) => total + item.harga * item.jumlah, 0);

    function showError(el, message) {
        el.textContent = message;
    }
    function hideError(el) {
        el.textContent = '';
    }

    function selectedBarang() {
        const option = barangSelect.options[barangSelect.selectedIndex];
        if (!option || !option.value) return null;
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

    function updateKembalian() {
        const raw = bayarInput.value;
        if (raw === '' || raw === null) {
            kembalianDisplay.textContent = '—';
            kembalianDisplay.className = 'kembalian-ok';
            return;
        }
        const diff = Number(raw) - cartTotal();
        if (diff >= 0) {
            kembalianDisplay.textContent = formatRupiah(diff);
            kembalianDisplay.className = 'kembalian-ok';
        } else {
            kembalianDisplay.textContent = 'Kurang ' + formatRupiah(Math.abs(diff));
            kembalianDisplay.className = 'kembalian-kurang';
        }
    }

    function renderCart() {
        cartBody.innerHTML = '';

        if (!cart.length) {
            cartBody.innerHTML = '<tr><td colspan="5" class="empty-cell">Keranjang masih kosong — pilih barang lalu tambahkan.</td></tr>';
        } else {
            cart.forEach((item, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${esc(item.nama)}</td>
                    <td class="col-num">${formatRupiah(item.harga)}</td>
                    <td>
                        <div class="qty">
                            <button type="button" class="qty-btn" data-action="dec" data-index="${index}"
                                    ${item.jumlah <= 1 ? 'disabled' : ''} aria-label="Kurangi jumlah ${esc(item.nama)}">
                                <i class="bi bi-dash" aria-hidden="true"></i>
                            </button>
                            <span class="qty-val">${item.jumlah}</span>
                            <button type="button" class="qty-btn" data-action="inc" data-index="${index}"
                                    ${item.jumlah >= item.stok ? 'disabled' : ''} aria-label="Tambah jumlah ${esc(item.nama)}">
                                <i class="bi bi-plus" aria-hidden="true"></i>
                            </button>
                        </div>
                    </td>
                    <td class="col-num">${formatRupiah(item.harga * item.jumlah)}</td>
                    <td class="col-act">
                        <button type="button" class="icon-btn text-danger" data-action="remove" data-index="${index}"
                                aria-label="Hapus ${esc(item.nama)} dari keranjang">
                            <i class="bi bi-trash" aria-hidden="true"></i>
                        </button>
                    </td>
                `;
                cartBody.appendChild(row);
            });
        }

        cartTotalEl.textContent = formatRupiah(cartTotal());
        cartCountEl.textContent = `${cart.length} item`;
        btnSimpan.disabled = !cart.length;
        if (cart.length) hideError(submitError);
        serializeCart();
        updateKembalian();
    }

    barangSelect.addEventListener('change', () => {
        const barang = selectedBarang();
        hargaDisplay.value = barang ? formatRupiah(barang.harga) : '—';
        hideError(itemError);
    });

    btnTambah.addEventListener('click', () => {
        hideError(itemError);
        const barang = selectedBarang();
        const jumlah = Number(jumlahInput.value);

        if (!barang) {
            showError(itemError, 'Pilih barang terlebih dahulu.');
            return;
        }
        if (!Number.isInteger(jumlah) || jumlah < 1) {
            showError(itemError, 'Jumlah minimal 1.');
            return;
        }

        const existing = cart.find((item) => item.barang_id === barang.barang_id);
        const newQty = existing ? existing.jumlah + jumlah : jumlah;

        if (newQty > barang.stok) {
            showError(itemError, `Stok ${barang.nama} tidak mencukupi — sisa stok ${barang.stok}.`);
            return;
        }

        if (existing) {
            existing.jumlah = newQty;
        } else {
            cart.push({ ...barang, jumlah });
        }

        jumlahInput.value = 1;
        renderCart();
    });

    cartBody.addEventListener('click', (event) => {
        const button = event.target.closest('button[data-action]');
        if (!button) return;
        const index = Number(button.dataset.index);
        const item = cart[index];
        if (!item) return;

        if (button.dataset.action === 'inc') {
            if (item.jumlah < item.stok) {
                item.jumlah += 1;
                hideError(itemError);
            } else {
                showError(itemError, `Stok ${item.nama} maksimal ${item.stok}.`);
            }
        } else if (button.dataset.action === 'dec') {
            item.jumlah = Math.max(1, item.jumlah - 1);
        } else if (button.dataset.action === 'remove') {
            cart.splice(index, 1);
        }
        renderCart();
    });

    bayarInput.addEventListener('input', updateKembalian);

    formTransaksi.addEventListener('submit', (event) => {
        serializeCart();
        if (!cart.length) {
            event.preventDefault();
            showError(submitError, 'Keranjang masih kosong — tambahkan minimal satu barang.');
        }
    });

    renderCart();
</script>
@endsection
