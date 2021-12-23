<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RestaurantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'=> $this->faker->unique->company(),
            'address'=> $this->faker->address(),
            'photo'=> $this->faker->imageUrl(),
        ];
    }
}
