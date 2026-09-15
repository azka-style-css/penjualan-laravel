<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['kode_barang' => 'BRG001', 'nama_barang' => 'Mouse Wireless', 'harga' => 75000, 'stok' => 20],
            ['kode_barang' => 'BRG002', 'nama_barang' => 'Keyboard', 'harga' => 120000, 'stok' => 15],
            ['kode_barang' => 'BRG003', 'nama_barang' => 'Flashdisk 32GB', 'harga' => 65000, 'stok' => 25],
            ['kode_barang' => 'BRG004', 'nama_barang' => 'Headset', 'harga' => 95000, 'stok' => 10],
            ['kode_barang' => 'BRG005', 'nama_barang' => 'Kabel Data', 'harga' => 35000, 'stok' => 30],
        ];

        foreach ($items as $item) {
            Barang::updateOrCreate(
                ['kode_barang' => $item['kode_barang']],
                $item
            );
        }
    }
}
