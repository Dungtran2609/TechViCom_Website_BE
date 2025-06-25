<?php

namespace Database\Factories;

use App\Models\ProductAllImage;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductAllImageFactory extends Factory
{
    protected $model = ProductAllImage::class;

    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'variant_id' => ProductVariant::factory(),
            'img_url' => $this->faker->imageUrl(),
            'is_primary' => $this->faker->boolean(),
            'created_at' => now(),
        ];
    }
}
