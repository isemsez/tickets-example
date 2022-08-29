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
            'to_do' => $this->faker->text(),

            'until' => $this->faker->dateTimeBetween('now', '+1 week'),

            'initiator' => rand(1,30),
//            'initiator' => User::factory()->create()->id,

            'doer' => rand(1,30),
//            'doer' => User::factory()->create()->id,

            'status' => $this->faker->randomElement(['open','progress','closed']),

        ];
    }
}
