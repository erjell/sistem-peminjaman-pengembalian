<?php

namespace App\Http\Controllers;

use App\Models\BorrowRecord;
use App\Models\Item;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    /**
     * Display a listing of borrow records.
     */
    public function index()
    {
        $records = BorrowRecord::with('item', 'user')->latest()->get();

        return view('borrow.index', compact('records'));
    }

    /**
     * Show the form for borrowing an item.
     */
    public function create()
    {
        $items = Item::all();

        return view('borrow.create', compact('items'));
    }
    /**
     * Borrow an item.
     */
    public function borrow(Request $request)
    {
        $data = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'user_id' => 'sometimes|exists:users,id',
        ]);

        $userId = $data['user_id'] ?? $request->user()?->id;

        $item = Item::findOrFail($data['item_id']);

        if ($item->stock < $data['quantity']) {
            $message = ['message' => 'Insufficient stock'];
            return $request->wantsJson() ? response()->json($message, 422) : back()->withErrors($message);
        }

        $item->decrement('stock', $data['quantity']);

        $record = BorrowRecord::create([
            'item_id' => $data['item_id'],
            'user_id' => $userId,
            'quantity' => $data['quantity'],
            'borrowed_at' => now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json($record);
        }

        return redirect()->route('borrow.index');
    }

    /**
     * Return an item.
     */
    public function returnItem(BorrowRecord $record)
    {
        if ($record->returned_at) {
            $message = ['message' => 'Item already returned'];
            return request()->wantsJson() ? response()->json($message, 422) : back()->withErrors($message);
        }

        $record->item->increment('stock', $record->quantity);
        $record->update(['returned_at' => now()]);

        if (request()->wantsJson()) {
            return response()->json($record);
        }

        return redirect()->route('borrow.index');
    }
}
