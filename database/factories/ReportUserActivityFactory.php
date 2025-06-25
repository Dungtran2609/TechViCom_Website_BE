<?php

namespace Database\Factories;

use App\Models\ReportUserActivity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportUserActivityFactory extends Factory
{
    protected $model = ReportUserActivity::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'activity_date' => $this->faker->date(),
            'login_count' => $this->faker->numberBetween(1, 10),
            'order_count' => $this->faker->numberBetween(1, 5),
            'comment_count' => $this->faker->numberBetween(0, 10),
            'page_views' => $this->faker->numberBetween(1, 100),
            'time_spent' => $this->faker->numberBetween(30, 300), // in seconds
            'interactions' => $this->faker->numberBetween(0, 10),
            'created_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}
