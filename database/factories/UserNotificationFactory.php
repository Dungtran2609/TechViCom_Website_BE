<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserNotification;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserNotificationFactory extends Factory
{
    protected $model = UserNotification::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'notification_id' => Notification::factory(),
            'is_read' => $this->faker->boolean(),
            'created_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}
