<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        return [
            'parent_id' => null,
            'name' => $this->faker->word(),
            'slug' => $this->faker->slug(),
        ];
    }
}
