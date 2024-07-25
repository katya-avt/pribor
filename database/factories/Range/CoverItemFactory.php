<?php

namespace Database\Factories\Range;

use App\Models\Range\CoverItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CoverItemFactory extends Factory
{
    protected $model = CoverItem::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'area' => fake()->randomFloat(2, 150, 180),
            'consumption' => fake()->randomFloat(5, 0.00001, 0.00008)
        ];
    }
}
