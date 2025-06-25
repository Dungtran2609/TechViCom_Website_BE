<?php

namespace Database\Factories;

use App\Models\ShippingMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingMethodFactory extends Factory
{
    protected $model = ShippingMethod::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'fee' => $this->faker->randomFloat(2, 10, 100),
            'estimated_days' => $this->faker->numberBetween(1, 7),
            'max_weight' => $this->faker->randomFloat(2, 0.5, 50),
            'regions' => $this->faker->state(),
        ];
    }
}
