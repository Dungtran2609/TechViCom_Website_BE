<?php

namespace Database\Factories;

use App\Models\NewsComment;
use App\Models\User;
use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsCommentFactory extends Factory
{
    protected $model = NewsComment::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'news_id' => News::factory(),
            'content' => $this->faker->text(),
            'is_hidden' => $this->faker->boolean(),
            'created_at' => now(),
        ];
    }
}
