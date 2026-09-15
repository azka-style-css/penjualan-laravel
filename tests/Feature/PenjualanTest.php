<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenjualanTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_add_new_barang(): void
    {
        $response = $this->post(route('barang.store'), [
            'kode_barang' => 'BRG006',
            'nama_barang' => 'Webcam',
            'harga' => 150000,
            'stok' => 10,
        ]);

        $response->assertRedirect(route('barang.index'));
        $this->assertDatabaseHas('barangs', [
            'kode_barang' => 'BRG006',
            'nama_barang' => 'Webcam',
            'harga' => 150000,
            'stok' => 10,
        ]);
    }

    public function test_can_update_barang_price(): void
    {
        $barang = Barang::create([
            'kode_barang' => 'BRG006',
            'nama_barang' => 'Webcam',
            'harga' => 150000,
            'stok' => 10,
        ]);

        $response = $this->put(route('barang.update', $barang->id), [
            'kode_barang' => 'BRG006',
            'nama_barang' => 'Webcam',
            'harga' => 175000,
            'stok' => 10,
        ]);

        $response->assertRedirect(route('barang.index'));
        $this->assertDatabaseHas('barangs', [
            'id' => $barang->id,
            'harga' => 175000,
        ]);
    }

    public function test_transaction_calculates_total_and_deducts_stock(): void
    {
        $mouse = Barang::create([
            'kode_barang' => 'BRG001',
            'nama_barang' => 'Mouse Wireless',
            'harga' => 75000,
            'stok' => 20,
        ]);

        $keyboard = Barang::create([
            'kode_barang' => 'BRG002',
            'nama_barang' => 'Keyboard',
            'harga' => 120000,
            'stok' => 15,
        ]);

        $response = $this->post(route('transaksi.store'), [
            'items' => [
                ['barang_id' => $mouse->id, 'jumlah' => 2],
                ['barang_id' => $keyboard->id, 'jumlah' => 1],
            ],
        ]);

        $transaksi = Transaksi::first();

        $this->assertNotNull($transaksi);
        $this->assertSame('TRX-0001', $transaksi->nomor_transaksi);
        $this->assertEquals(270000, (float) $transaksi->total);
        $this->assertEquals(18, $mouse->fresh()->stok);
        $this->assertEquals(14, $keyboard->fresh()->stok);
        $response->assertRedirect(route('transaksi.show', $transaksi->id));
    }

    public function test_transaction_rejected_when_stock_insufficient(): void
    {
        $keyboard = Barang::create([
            'kode_barang' => 'BRG002',
            'nama_barang' => 'Keyboard',
            'harga' => 120000,
            'stok' => 2,
        ]);

        $response = $this->from(route('transaksi.create'))->post(route('transaksi.store'), [
            'items' => [
                ['barang_id' => $keyboard->id, 'jumlah' => 5],
            ],
        ]);

        $response->assertRedirect(route('transaksi.create'));
        $response->assertSessionHas('error', 'Stok Keyboard tidak mencukupi.');
        $this->assertDatabaseCount('transaksis', 0);
        $this->assertEquals(2, $keyboard->fresh()->stok);
    }
}
