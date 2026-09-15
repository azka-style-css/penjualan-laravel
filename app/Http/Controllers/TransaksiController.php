<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class TransaksiController extends Controller
{
    public function index(Request $request): View
    {
        $tanggalAwal = $request->query('tanggal_awal');
        $tanggalAkhir = $request->query('tanggal_akhir');

        $transaksis = Transaksi::query()
            ->when($tanggalAwal, fn ($query) => $query->whereDate('tanggal', '>=', $tanggalAwal))
            ->when($tanggalAkhir, fn ($query) => $query->whereDate('tanggal', '<=', $tanggalAkhir))
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('transaksi.index', compact('transaksis', 'tanggalAwal', 'tanggalAkhir'));
    }

    public function create(): View
    {
        $barangs = Barang::query()
            ->orderBy('nama_barang')
            ->get(['id', 'kode_barang', 'nama_barang', 'harga', 'stok']);

        $nomorTransaksi = $this->peekNextNomorTransaksi();

        return view('transaksi.create', compact('barangs', 'nomorTransaksi'));
    }

    public function store(Request $request): RedirectResponse
    {
        $payload = $request->input('items');

        if (is_string($payload)) {
            $decoded = json_decode($payload, true);
            $request->merge(['items' => is_array($decoded) ? $decoded : []]);
        }

        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.barang_id' => ['required', 'exists:barangs,id'],
            'items.*.jumlah' => ['required', 'integer', 'min:1'],
        ], [
            'items.required' => 'Keranjang masih kosong. Tambahkan minimal satu barang.',
            'items.min' => 'Keranjang masih kosong. Tambahkan minimal satu barang.',
        ]);

        try {
            $transaksi = DB::transaction(function () use ($validated) {
                $items = $validated['items'];

                foreach ($items as $item) {
                    $barang = Barang::lockForUpdate()->findOrFail($item['barang_id']);

                    if ($barang->stok < $item['jumlah']) {
                        throw new \Exception("Stok {$barang->nama_barang} tidak mencukupi.");
                    }
                }

                $nomorTransaksi = $this->generateNomorTransaksi();
                $total = 0;
                $prepared = [];

                foreach ($items as $item) {
                    $barang = Barang::lockForUpdate()->findOrFail($item['barang_id']);
                    $jumlah = (int) $item['jumlah'];
                    $harga = (float) $barang->harga;
                    $subtotal = $harga * $jumlah;
                    $total += $subtotal;

                    $prepared[] = [
                        'barang' => $barang,
                        'harga' => $harga,
                        'jumlah' => $jumlah,
                        'subtotal' => $subtotal,
                    ];
                }

                $transaksi = Transaksi::create([
                    'nomor_transaksi' => $nomorTransaksi,
                    'tanggal' => now()->toDateString(),
                    'total' => $total,
                ]);

                foreach ($prepared as $row) {
                    DetailTransaksi::create([
                        'transaksi_id' => $transaksi->id,
                        'barang_id' => $row['barang']->id,
                        'harga' => $row['harga'],
                        'jumlah' => $row['jumlah'],
                        'subtotal' => $row['subtotal'],
                    ]);

                    $row['barang']->decrement('stok', $row['jumlah']);
                }

                return $transaksi;
            });
        } catch (Throwable $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('transaksi.show', $transaksi->id)
            ->with('success', 'Transaksi berhasil disimpan.');
    }

    public function show(string $id): View
    {
        $transaksi = Transaksi::with(['detailTransaksis.barang'])->findOrFail($id);
        $grandTotal = $transaksi->detailTransaksis->sum('subtotal');

        return view('transaksi.show', compact('transaksi', 'grandTotal'));
    }

    private function peekNextNomorTransaksi(): string
    {
        $last = Transaksi::query()->orderByDesc('id')->first();
        $newNumber = $last ? ((int) substr($last->nomor_transaksi, 4) + 1) : 1;

        return 'TRX-' . str_pad((string) $newNumber, 4, '0', STR_PAD_LEFT);
    }

    private function generateNomorTransaksi(): string
    {
        $last = Transaksi::query()->lockForUpdate()->orderByDesc('id')->first();
        $newNumber = $last ? ((int) substr($last->nomor_transaksi, 4) + 1) : 1;

        return 'TRX-' . str_pad((string) $newNumber, 4, '0', STR_PAD_LEFT);
    }
}
