<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'note' => $this->faker->text(60),

            'do_until' => $this->faker->dateTimeBetween('+3 days', '+3 weeks'),

            'initiator' => rand(1,30),

            'doer' => function(array $attributes) {
                if ($this->faker->boolean(25)) {
                    return $attributes['initiator'];
                } else {
                    return rand(1,30);
                }
            },

            'status' => $this->faker->randomElement(['open','progress','closed']),

        ];
    }
}
