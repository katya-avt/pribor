<?php

namespace Database\Factories\Range;

use App\Models\Range\Operation;
use App\Models\Range\Point;
use App\Models\Range\Rate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RoutePointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'point_code' => Point::all()->random()->code,
            'operation_code' => Operation::all()->random()->code,
            'rate_code' => Rate::all()->random()->code,
            'unit_time' => fake()->randomFloat(2, 2, 20),
            'working_time' => fake()->randomFloat(2, 2, 20),
            'lead_time' => fake()->randomFloat(2, 2, 10)
        ];
    }
}
