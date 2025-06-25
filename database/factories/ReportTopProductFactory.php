<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ReportTopProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportTopProductFactory extends Factory
{
    protected $model = ReportTopProduct::class;

    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'sold_quantity' => $this->faker->numberBetween(10, 500),
            'report_date' => $this->faker->date(),
        ];
    }
}
