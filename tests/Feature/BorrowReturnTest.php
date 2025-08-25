<?php

use App\Models\Item;
use App\Models\User;
use App\Models\BorrowRecord;
use Illuminate\Support\Facades\Storage;

it('creates an item with barcode', function () {
    Storage::fake('public');

    $response = $this->postJson('/api/items', [
        'name' => 'Camera',
        'description' => '4K camera',
        'condition' => 'baik',
        'stock' => 5,
    ]);

    $response->assertCreated();
    $item = Item::first();
    expect($item->barcode)->not->toBeNull();
    expect(Storage::disk('public')->exists($item->barcode_path))->toBeTrue();
});

it('borrows and returns an item', function () {
    Storage::fake('public');
    $item = Item::factory()->create(['stock' => 10]);
    $user = User::factory()->create();

    $borrow = $this->postJson('/api/borrow', [
        'item_id' => $item->id,
        'user_id' => $user->id,
        'quantity' => 2,
    ]);

    $borrow->assertOk();
    $item->refresh();
    expect($item->stock)->toBe(8);

    $record = BorrowRecord::first();
    $return = $this->postJson('/api/return/'.$record->id);

    $return->assertOk();
    $item->refresh();
    expect($item->stock)->toBe(10);
    expect($record->fresh()->returned_at)->not->toBeNull();
});

