<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use App\Models\ProductVariantsAttribute;
use App\Models\AttributeValue;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantsAttributeFactory extends Factory
{
    protected $model = ProductVariantsAttribute::class;

    public function definition()
    {
        return [
            'product_variant_id' => ProductVariant::factory(),
            'attribute_value_id' => AttributeValue::factory(),
        ];
    }
}
