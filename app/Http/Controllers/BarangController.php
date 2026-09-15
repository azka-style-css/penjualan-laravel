<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BarangController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $barangs = Barang::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('nama_barang', 'like', "%{$search}%")
                        ->orWhere('kode_barang', 'like', "%{$search}%");
                });
            })
            ->orderBy('kode_barang')
            ->get();

        return view('barang.index', compact('barangs', 'search'));
    }

    public function create(): View
    {
        return view('barang.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        Barang::create($validated);

        return redirect()
            ->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(string $id): RedirectResponse
    {
        return redirect()->route('barang.edit', $id);
    }

    public function edit(string $id): View
    {
        $barang = Barang::findOrFail($id);

        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $barang = Barang::findOrFail($id);

        $validated = $request->validate($this->rules($barang->id));

        $barang->update($validated);

        return redirect()
            ->route('barang.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $barang = Barang::findOrFail($id);

        if ($barang->detailTransaksis()->exists()) {
            return redirect()
                ->route('barang.index')
                ->with('error', 'Barang tidak dapat dihapus karena sudah digunakan pada transaksi.');
        }

        $barang->delete();

        return redirect()
            ->route('barang.index')
            ->with('success', 'Barang berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(?int $ignoreId = null): array
    {
        $uniqueKode = 'unique:barangs,kode_barang';

        if ($ignoreId !== null) {
            $uniqueKode .= ',' . $ignoreId;
        }

        return [
            'kode_barang' => ['required', 'string', 'max:20', $uniqueKode],
            'nama_barang' => ['required', 'string', 'max:100'],
            'harga' => ['required', 'numeric', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
        ];
    }
}
