<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'sku' => $this->faker->unique()->word(),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'sale_price' => $this->faker->randomFloat(2, 5, 450),
            'weight' => $this->faker->randomFloat(2, 0.1, 2),
            'dimensions' => '15x15x10 cm',
            'stock' => $this->faker->numberBetween(1, 50),
            'image' => 'variant_image.jpg',
        ];
    }
}
