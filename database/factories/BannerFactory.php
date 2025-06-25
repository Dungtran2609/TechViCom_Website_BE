<?php

namespace Database\Factories;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Factories\Factory;

class BannerFactory extends Factory
{
    protected $model = Banner::class;

    public function definition()
    {
        return [
            'image_url' => $this->faker->imageUrl(),
            'link_url' => $this->faker->url(),
            'position' => $this->faker->randomElement(['top', 'bottom', 'left', 'right']),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
