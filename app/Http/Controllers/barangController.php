<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class barangController extends Controller
{
    public function create()
{
    return view('barang');
}

public function store(Request $request)
{
    $dataBarang = $request->input('barang');

    foreach ($dataBarang as $item) {
        // Simpan ke database (contoh model: Barang)
        Barang::create([
            'barcode' => $item['barcode'],
            'nama' => $item['nama'],
            'jumlah' => $item['jumlah'],
        ]);
    }

    return redirect()->route('barang.create')->with('success', 'Data berhasil disimpan!');
}

}
