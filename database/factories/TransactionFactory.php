<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        return [
            'order_id' => Order::factory(),
            'payment_gateway' => $this->faker->word(),
            'transaction_code' => $this->faker->unique()->word(),
            'amount' => $this->faker->randomFloat(2, 1, 1000),
            'status' => $this->faker->randomElement(['pending', 'success', 'failed']),
            'transaction_type' => $this->faker->randomElement(['credit', 'debit']),
            'paid_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}
