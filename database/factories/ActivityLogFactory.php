<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityLogFactory extends Factory
{
    protected $model = ActivityLog::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),  // Tạo dữ liệu người dùng giả từ UserFactory
            'action_type' => $this->faker->randomElement(['create', 'update', 'delete', 'view']),
            'description' => $this->faker->sentence(),
            'target_table' => $this->faker->randomElement(['users', 'products', 'orders']),
            'target_id' => $this->faker->numberBetween(1, 100),
            'created_at' => $this->faker->dateTimeThisYear(),
            'updated_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}
