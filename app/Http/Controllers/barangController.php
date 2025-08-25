<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class barangController extends Controller
{
    public function create()
    {
        $barangList = [
            '123456' => ['nama' => 'Kamera Sony A6400', 'kategori' => 'Kamera', 'kondisi' => 'Baik'],
            '789012' => ['nama' => 'Tripod Velbon EX-540', 'kategori' => 'Aksesoris', 'kondisi' => 'Baik'],
            '345678' => ['nama' => 'Mic Rode Wireless GO', 'kategori' => 'Audio', 'kondisi' => 'Rusak'],
        ];

        return view('barang', compact('barangList'));
    }

    public function createMobile()
    {
        $barangList = [
            '123456' => ['nama' => 'Kamera Sony A6400', 'kategori' => 'Kamera', 'kondisi' => 'Baik'],
            '789012' => ['nama' => 'Tripod Velbon EX-540', 'kategori' => 'Aksesoris', 'kondisi' => 'Baik'],
            '345678' => ['nama' => 'Mic Rode Wireless GO', 'kategori' => 'Audio', 'kondisi' => 'Rusak'],
        ];

        return view('mobile.barang', compact('barangList'));
    }

    public function store(Request $request)
    {
        try {
            $dataBarang = $request->input('barang', []);

            foreach ($dataBarang as $item) {
                Barang::create([
                    'barcode' => $item['barcode'],
                    'nama' => $item['nama'],
                    'jumlah' => $item['jumlah'],
                ]);
            }

            return redirect()->route('barang.create')->with('success', 'Data berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->route('barang.create')->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }
}
