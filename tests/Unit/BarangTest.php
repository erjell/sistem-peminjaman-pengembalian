<?php

use App\Models\Barang;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function Pest\Laravel\assertDatabaseHas;

uses(TestCase::class, RefreshDatabase::class);

test('it can create barang', function () {
    $data = [
        'barcode' => 'ABC123',
        'nama' => 'Contoh Barang',
        'jumlah' => 5,
    ];

    $barang = Barang::create($data);

    expect($barang->exists)->toBeTrue()
        ->and($barang->barcode)->toBe($data['barcode'])
        ->and($barang->nama)->toBe($data['nama'])
        ->and($barang->jumlah)->toBe($data['jumlah']);

    assertDatabaseHas('barangs', $data);
});
