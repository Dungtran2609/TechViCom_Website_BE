<?php

namespace Database\Factories;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    protected $model = News::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->text(),
            'author_id' => User::factory(),
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
            'published_at' => now(),
        ];
    }
}
