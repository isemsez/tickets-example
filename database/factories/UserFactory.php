<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_name' => strtolower($this->faker->firstName),

            'password' => $this->faker->password(8,12),

            'full_name' => $this->faker->name,

            'rights' => function() {
                if ($this->faker->boolean(5)) {
                    return 'admin';
                } else {
                    return 'user';
                }
            }
        ];
    }
}

