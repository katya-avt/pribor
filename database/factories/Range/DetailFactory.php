<?php

namespace Database\Factories\Range;

use App\Models\Range\Detail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\range\Detail>
 */
class DetailFactory extends Factory
{
    protected $model = Detail::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $separator = 'x';
        $length = fake()->numberBetween(10, 150);
        $width = fake()->numberBetween(10, 150);
        $height = fake()->numberBetween(10, 150);

        return [
            'detail_size' => $length . $separator . $width . $separator . $height,
            'billet_size' => $length + 3 . $separator . $width + 6 . $separator . $height + 6
        ];
    }
}
