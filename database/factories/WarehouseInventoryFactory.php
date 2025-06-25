<?php

namespace Database\Factories;

use App\Models\Warehouse;
use App\Models\ProductVariant;
use App\Models\WarehouseInventory;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseInventoryFactory extends Factory
{
    protected $model = WarehouseInventory::class;

    public function definition()
    {
        return [
            'warehouse_id' => Warehouse::factory(),
            'product_variant_id' => ProductVariant::factory(),
            'stock' => $this->faker->numberBetween(1, 100),
        ];
    }
}
