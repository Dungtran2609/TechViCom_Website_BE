<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\RefundRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RefundRequestFactory extends Factory
{
    protected $model = RefundRequest::class;

    public function definition()
    {
        return [
            'order_id' => Order::factory(),
            'user_id' => User::factory(),
            'reason' => $this->faker->text(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'created_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}
