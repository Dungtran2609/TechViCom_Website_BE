<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'name' => $this->faker->word(),
            'sku' => $this->faker->unique()->word(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'discount_price' => $this->faker->randomFloat(2, 5, 900),
            'weight' => $this->faker->randomFloat(2, 0.5, 5),
            'dimensions' => '30x20x10 cm',
            'stock' => $this->faker->numberBetween(1, 100),
            'description' => $this->faker->text(),
            'status' => $this->faker->randomElement(['available', 'out_of_stock', 'discontinued']),
            'created_by' => User::factory(),
        ];
    }
}
