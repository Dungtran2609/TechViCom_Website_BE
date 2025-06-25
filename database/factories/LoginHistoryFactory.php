<?php

namespace Database\Factories;

use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoginHistoryFactory extends Factory
{
    protected $model = LoginHistory::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'ip_address' => $this->faker->ipv4(),
            'device_info' => $this->faker->userAgent(),
            'login_status' => $this->faker->randomElement(['success', 'failed']),
            'created_at' => now(),
        ];
    }
}
