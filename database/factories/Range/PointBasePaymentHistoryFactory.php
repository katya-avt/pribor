<?php

namespace Database\Factories\Range;

use App\Models\Range\Point;
use App\Models\Range\PointBasePaymentHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\range\PointBasePaymentHistory>
 */
class PointBasePaymentHistoryFactory extends Factory
{
    protected $model = PointBasePaymentHistory::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'point_code' => Point::all()->random()->code,
            'change_time' => fake()->unique()->dateTimeBetween('-1 years', 'now'),
            'new_value' => fake()->randomFloat(2, 4, 6)
        ];
    }
}
