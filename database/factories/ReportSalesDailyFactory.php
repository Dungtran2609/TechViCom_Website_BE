<?php

namespace Database\Factories;

use App\Models\ReportSalesDaily;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportSalesDailyFactory extends Factory
{
    protected $model = ReportSalesDaily::class;

    public function definition()
    {
        return [
            'report_date' => $this->faker->date(),
            'total_orders' => $this->faker->numberBetween(1, 100),
            'total_revenue' => $this->faker->randomFloat(2, 100, 10000),
            'total_refunds' => $this->faker->randomFloat(2, 0, 1000),
            'created_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}
