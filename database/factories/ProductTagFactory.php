<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductTag;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductTagFactory extends Factory
{
    protected $model = ProductTag::class;

    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'tag_id' => Tag::factory(),
        ];
    }
}
