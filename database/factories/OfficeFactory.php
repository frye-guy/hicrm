<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OfficeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'    => $this->faker->city() . ' Office',
            'phone'   => $this->faker->numerify('###-###-####'),
            'address' => $this->faker->streetAddress() . ', ' . $this->faker->city() . ', ' . $this->faker->stateAbbr(),
            'lat'     => $this->faker->randomFloat(7, 25, 49),
            'lng'     => $this->faker->randomFloat(7, -124, -66),
        ];
    }
}
