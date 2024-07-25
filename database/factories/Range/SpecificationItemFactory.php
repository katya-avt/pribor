<?php

namespace Database\Factories\Range;

use App\Models\Range\Item;
use App\Models\Range\SpecificationItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\range\SpecificationItem>
 */
class SpecificationItemFactory extends Factory
{
    protected $model = SpecificationItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (SpecificationItem $specificationItem) {
            $item = Item::find($specificationItem->item_id);

            $cnt = $item->unit->isUnit() ? fake()->numberBetween(1, 5) :
                fake()->randomFloat(5, 0.1, 0.98);

            $specificationItem->cnt = $cnt;

        })->afterCreating(function (SpecificationItem $specificationItem) {
        });
    }
}
