<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password123'),  // Mã hóa mật khẩu
            'phone_number' => $this->faker->phoneNumber(),
            'image_profile' => 'profile_image.jpg',
            'is_active' => true,
            'birthday' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['Male', 'Female', 'Non-Binary', 'Other']),
        ];
    }
}
