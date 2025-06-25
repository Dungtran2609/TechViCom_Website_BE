<?php

namespace Database\Factories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition()
    {
        return [
            'config_key' => $this->faker->word(),
            'config_value' => $this->faker->text(),
            'updated_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}
