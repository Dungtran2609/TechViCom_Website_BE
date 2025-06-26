<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAddressFactory extends Factory
{
    protected $model = UserAddress::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'address_line' => $this->faker->address(),
            'ward' => $this->faker->word(),
            'district' => $this->faker->word(),
            'city' => $this->faker->city(),
            'is_default' => $this->faker->boolean(),
            'created_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}
