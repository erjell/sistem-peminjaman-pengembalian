<?php

namespace Database\Factories;

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
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'condition' => 'baik',
            'stock' => 10,
            'barcode' => strtoupper(Str::random(10)),
            'barcode_path' => 'barcodes/' . Str::random(10) . '.png',
        ];
    }
}
