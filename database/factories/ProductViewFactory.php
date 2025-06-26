<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductView;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductViewFactory extends Factory
{
    protected $model = ProductView::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'viewed_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}
