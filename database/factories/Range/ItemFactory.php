<?php

namespace Database\Factories\Range;

use App\Models\Range\Cover;
use App\Models\Range\Detail;
use App\Models\Range\Group;
use App\Models\Range\Item;
use App\Models\Range\PurchasedItem;
use App\Models\Range\Route;
use App\Models\Range\Specification;
use App\Models\Range\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\range\Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;
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

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Item $item) {

            $cnt = $item->unit->isUnit() ? fake()->numberBetween(1, 20) :
                fake()->randomFloat(2, 0.1, 0.98);

            $item->cnt = $cnt;

            if ($item->specification_number) {
                Specification::firstOrCreate(
                    ['number' => $item->specification_number],
                    ['valid_from' => fake()->dateTimeBetween('-12 month', '-9 month')->format('Y-m-d')]
                );
            }

            if ($item->cover_number) {
                Cover::firstOrCreate(
                    ['number' => $item->cover_number],
                    ['valid_from' => fake()->dateTimeBetween('-12 month', '-9 month')->format('Y-m-d')]
                );
            }

            if ($item->route_number) {
                Route::firstOrCreate([
                    'number' => $item->route_number
                ]);
            }

        })->afterCreating(function (Item $item) {

            if ($item->group->isDetail()) {
                Detail::factory()->create([
                    'item_id' => $item->id
                ]);
            }

            if ($item->itemType->isPurchased()) {

                if ($item->unit->short_name == Unit::U) {
                    $purchaseLot = fake()->numberBetween(2, 50);
                    $orderPoint = fake()->numberBetween(2, 50);
                } else {
                    $purchaseLot = fake()->randomFloat(2, 2, 50);
                    $orderPoint = fake()->randomFloat(2, 2, 50);
                }

                $groupName = $item->group->name;
                $unitShortName = Group::getGroupsAndPurchasedItemUnitsMatching()[$groupName];
                $unit = Unit::where('short_name', $unitShortName)->first();

                $unitFactor = fake()->randomFloat(2, 2, 50);

                if ($item->unit_code == $unit->code) {
                    $unitFactor = 1;
                }

                if ($item->unit->short_name == Unit::KG && $unit->short_name == Unit::T) {
                    $unitFactor = 1000;
                }
                PurchasedItem::factory()->create([
                    'item_id' => $item->id,
                    'purchase_lot' => $purchaseLot,
                    'order_point' => $orderPoint,
                    'unit_factor' => $unitFactor,
                    'unit_code' => $unit->short_name
                ]);
            }

            if ($item->specification_number) {
                $item->specifications()->attach($item->specification_number);
            }

            if ($item->cover_number) {
                $item->covers()->attach($item->cover_number);
            }

            if ($item->route_number) {
                $item->routes()->attach($item->route_number);
            }
        });
    }
}
