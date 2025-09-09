<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'plate_number' => $this->faker->regexify('[A-Z]{3}-[0-9]{3}'),
            'model' => $this->faker->word() . ' ' . $this->faker->randomElement(['Sedan', 'Truck', 'Van']),
        ];
    }
}
