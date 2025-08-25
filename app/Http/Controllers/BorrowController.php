<?php

namespace App\Http\Controllers;

use App\Models\BorrowRecord;
use App\Models\Item;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    /**
     * Borrow an item.
     */
    public function borrow(Request $request)
    {
        $data = $request->validate([
            'item_id' => 'required|exists:items,id',
            'user_id' => 'required|exists:users,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = Item::findOrFail($data['item_id']);

        if ($item->stock < $data['quantity']) {
            return response()->json(['message' => 'Insufficient stock'], 422);
        }

        $item->decrement('stock', $data['quantity']);

        $record = BorrowRecord::create([
            'item_id' => $data['item_id'],
            'user_id' => $data['user_id'],
            'quantity' => $data['quantity'],
            'borrowed_at' => now(),
        ]);

        return response()->json($record);
    }

    /**
     * Return an item.
     */
    public function returnItem(BorrowRecord $record)
    {
        if ($record->returned_at) {
            return response()->json(['message' => 'Item already returned'], 422);
        }

        $record->item->increment('stock', $record->quantity);
        $record->update(['returned_at' => now()]);

        return response()->json($record);
    }
}
