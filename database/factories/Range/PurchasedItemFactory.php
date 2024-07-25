<?php

namespace Database\Factories\Range;

use App\Models\Range\PurchasedItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\range\PurchasedItem>
 */
class PurchasedItemFactory extends Factory
{
    protected $model = PurchasedItem::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'purchase_price' => fake()->randomFloat(2, 70, 250)
        ];
    }
}
