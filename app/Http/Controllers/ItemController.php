<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Milon\Barcode\DNS1D;

class ItemController extends Controller
{
    /**
     * Store a newly created item in storage and generate its barcode.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'condition' => 'nullable|string|max:255',
            'stock' => 'required|integer|min:0',
        ]);

        $code = strtoupper(Str::random(10));
        $path = 'barcodes/' . $code . '.png';
        $png = (new DNS1D())->getBarcodePNG($code, 'C128');
        Storage::disk('public')->put($path, base64_decode($png));

        $item = Item::create(array_merge($data, [
            'barcode' => $code,
            'barcode_path' => $path,
        ]));

        return response()->json($item, 201);
    }
}
