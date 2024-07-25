<?php

namespace Database\Factories\Range;

use App\Models\Range\Item;
use App\Models\Range\PurchasedItemPurchasePriceHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\range\PurchasedItemPurchasePriceHistory>
 */
class PurchasedItemPurchasePriceHistoryFactory extends Factory
{
    protected $model = PurchasedItemPurchasePriceHistory::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'item_id' => Item::has('purchasedItem')->get()->random()->id,
            'change_time' => fake()->unique()->dateTimeBetween('-1 years', 'now'),
            'new_value' => fake()->randomFloat(2, 70, 250)
        ];
    }
}
