<?php

namespace Database\Factories;

use App\Enums\TripStatus;
use App\Models\Company;
use App\Models\Driver;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $start = Carbon::instance($this->faker->dateTimeBetween('now +1 day', 'now +30 days'));
        $company = Company::inRandomOrder()->first();
        $driver = Driver::inRandomOrder()->first();
        $vehicle = Vehicle::inRandomOrder()->first();

        return [
            'company_id' => $company->id,
            'driver_id' => $driver->id,
            'vehicle_id' => $vehicle->id,
            'start_time' => $start,
            'end_time' => $start->copy()->addHours(2),
            'status' => $this->faker->randomElement(TripStatus::values()),
            'description' => $this->faker->sentence(),
        ];
    }
}
