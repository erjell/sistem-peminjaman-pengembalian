<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Item> */
class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper(Str::random(8)),
            'name' => $this->faker->word(),
            'serial_number' => strtoupper(Str::random(8)),
            'procurement_year' => $this->faker->year(),
            'description' => $this->faker->sentence(),
            'condition' => 'baik',
            'stock' => 10,
            'barcode' => strtoupper(Str::random(10)),
            'barcode_path' => 'barcodes/' . Str::random(10) . '.png',
            'category_id' => Category::factory(),
        ];
    }
}
