<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition()
    {
        return [
            'code' => strtoupper($this->faker->lexify('??????')),
            'discount_type' => $this->faker->randomElement(['percentage', 'fixed']),
            'value' => $this->faker->randomFloat(2, 5, 50),
            'max_discount_amount' => $this->faker->randomFloat(2, 100, 500),
            'min_order_value' => $this->faker->randomFloat(2, 50, 200),
            'max_order_value' => $this->faker->randomFloat(2, 1000, 5000),
            'max_usage_per_user' => $this->faker->numberBetween(1, 10),
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'status' => $this->faker->boolean(),
        ];
    }
}
