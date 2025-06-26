<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductComment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductCommentFactory extends Factory
{
    protected $model = ProductComment::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'content' => $this->faker->paragraph(),
            'is_hidden' => $this->faker->boolean(),
        ];
    }
}
