<?php

use App\Enums\TripStatus;
use App\Filament\Widgets\KPIs;
use App\Models\Company;
use App\Models\Driver;
use App\Models\Trip;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows correct KPI counts', function () {
    $company = Company::factory()->create();
    $driver1 = Driver::factory()->create(['company_id' => $company->id]);
    $driver2 = Driver::factory()->create(['company_id' => $company->id]);
    $vehicle1 = Vehicle::factory()->create(['company_id' => $company->id]);
    $vehicle2 = Vehicle::factory()->create(['company_id' => $company->id]);

    // MS- Active trip : 1 active, driver1 & vehicle1 busy
    Trip::create([
        'company_id' => $company->id,
        'driver_id' => $driver1->id,
        'vehicle_id' => $vehicle1->id,
        'start_time' => now()->subHour(),
        'end_time' => now()->addHour(),
        'status' => TripStatus::Active,
    ]);

    // MS- Completed trip this month
    Trip::create([
        'company_id' => $company->id,
        'driver_id' => $driver2->id,
        'vehicle_id' => $vehicle2->id,
        'start_time' => now()->subDays(3),
        'end_time' => now()->subDays(2),
        'status' => TripStatus::Completed,
    ]);

    // MS- Instantiate widget
    $widget = new KPIs();

    // MS- Grab stats
    $stats = collect($widget->getStats());

    // MS- Assert counts
    expect($stats[0]->getValue())->toBe(1); // MS- Active Trips
    expect($stats[1]->getValue())->toBe(1); // MS- Available Drivers 
    expect($stats[2]->getValue())->toBe(1); // MS- Available Vehicles 
    expect($stats[3]->getValue())->toBe(1); // MS- Completed Trips this month
});
