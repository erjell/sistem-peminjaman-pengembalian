<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'serial_number',
        'procurement_year',
        'description',
        'condition',
        'stock',
        'barcode',
        'barcode_path',
        'category_id',
    ];

    /**
     * Get borrow records for the item.
     */
    public function borrowRecords(): HasMany
    {
        return $this->hasMany(BorrowRecord::class);
    }

    /**
     * Get the category that owns the item.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
