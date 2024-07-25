<?php

namespace Database\Factories\Orders;

use App\Models\Orders\Customer;
use App\Models\Orders\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\orders\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'code' => '1-' . fake()->unique()->numberBetween(1, 150),
            'name' => fake()->unique()->numberBetween(1, 150) . 'А',
            'creation_date' => fake()->dateTimeBetween('-8 month', '-5 month')->format('Y-m-d'),
            'closing_date' => fake()->dateTimeBetween('-4 month', '-1 month')->format('Y-m-d'),
            'customer_inn' => Customer::all()->random()->name
        ];
    }
}
