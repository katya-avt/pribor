<?php

namespace Database\Factories\Orders;

use App\Models\Orders\OrderItem;
use App\Models\Range\Group;
use App\Models\Range\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\orders\OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'item_id' => Item::whereHas('group', function ($query) {
                $query->whereIn('name', [Group::ASSEMBLY_ITEM, Group::DETAIL, Group::VARIOUS]);
            })->get()->random()->drawing,
            'per_unit_price' => fake()->randomFloat(2, 5000, 15000)
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (OrderItem $orderItem) {

            $item = Item::getItemByDrawing($orderItem->item_id);

            $cnt = $item->unit->isUnit() ? fake()->numberBetween(50, 100) :
                fake()->randomFloat(2, 1, 50);

            $orderItem->cnt = $cnt;

            if ($item->group->isAssemblyItem()) {
                $orderItem->per_unit_price = fake()->randomFloat(2, 40000, 50000);
            }

        })->afterCreating(function (OrderItem $orderItem) {
        });
    }
}
