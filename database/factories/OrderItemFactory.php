<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition()
    {
        return [
            'order_id' => Order::factory(),
            'variant_id' => ProductVariant::factory(),
            'quantity' => $this->faker->numberBetween(1, 5),
            'product_id' => Product::factory(),
            'image_product' => $this->faker->imageUrl(),
            'name_product' => $this->faker->word(),
            'price' => $this->faker->randomFloat(2, 5, 100),
        ];
    }
}
