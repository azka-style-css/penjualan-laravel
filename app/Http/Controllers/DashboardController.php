<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalBarang = Barang::count();
        $totalTransaksi = Transaksi::count();
        $totalPenjualan = Transaksi::sum('total');
        $lowStockBarangs = Barang::query()
            ->where('stok', '<=', 5)
            ->orderBy('stok')
            ->orderBy('nama_barang')
            ->get();

        return view('dashboard', compact(
            'totalBarang',
            'totalTransaksi',
            'totalPenjualan',
            'lowStockBarangs'
        ));
    }
}
