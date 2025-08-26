<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Milon\Barcode\DNS1D;

class ItemController extends Controller
{
    /**
     * Display item management page.
     */
    public function index()
    {
        $categories = Category::all();
        $items = Item::with('category')->get();
        return view('items.index', compact('categories', 'items'));
    }

    /**
     * Store a newly created item in storage and generate its barcode.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'procurement_year' => 'nullable|digits:4',
            'description' => 'nullable|string',
            'condition' => 'nullable|string|max:255',
            'stock' => 'nullable|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        if ($request->filled('category_id')) {
            $category = Category::find($request->category_id);
            $count = Item::where('category_id', $category->id)->count() + 1;
            $data['code'] = $category->code.'-'.str_pad($count, 4, '0', STR_PAD_LEFT);
        }

        $code = strtoupper(Str::random(10));
        $path = 'barcodes/' . $code . '.png';
        $png = (new DNS1D())->getBarcodePNG($code, 'C128');
        Storage::disk('public')->put($path, base64_decode($png));

        $item = Item::create(array_merge($data, [
            'barcode' => $code,
            'barcode_path' => $path,
        ]));

        if ($request->wantsJson()) {
            return response()->json($item, 201);
        }

        return redirect()->route('items.index')->with('success', 'Item created successfully');
    }

    /**
     * Generate next item code based on category.
     */
    public function generateCode(Category $category)
    {
        $count = Item::where('category_id', $category->id)->count() + 1;
        $code = $category->code.'-'.str_pad($count, 4, '0', STR_PAD_LEFT);
        return response()->json(['code' => $code]);
    }
}
