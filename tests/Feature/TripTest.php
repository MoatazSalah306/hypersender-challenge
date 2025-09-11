<?php

use App\Enums\TripStatus;
use App\Models\Company;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Trip;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('detects overlapping trips for the same driver and vehicle', function () {
    $company = Company::factory()->create();
    $driver = Driver::factory()->create(['company_id' => $company->id]);
    $vehicle = Vehicle::factory()->create(['company_id' => $company->id]);

    // MS- First trip (valid)
    Trip::create([
        'company_id' => $company->id,
        'driver_id' => $driver->id,
        'vehicle_id' => $vehicle->id,
        'start_time' => now(), // 11 pm
        'end_time' => now()->addHour(), // 12 am
        'status' => TripStatus::Active,
    ]);

    // MS- Prepare overlapping trip data (not saved yet)
    $trip2 = [
        'company_id' => $company->id,
        'driver_id' => $driver->id,
        'vehicle_id' => $vehicle->id,
        'start_time' => now()->addMinutes(30),   // MS- overlaps with trip1 - 11:30 pm
        'end_time' => now()->addHours(2), 
        'status' => TripStatus::Active,
    ];

    expect(Trip::hasOverlap($trip2))->toBeTrue();
});

it('doesn\'t detect overlap when trips are separate', function () {
    $company = Company::factory()->create();
    $driver = Driver::factory()->create(['company_id' => $company->id]);
    $vehicle = Vehicle::factory()->create(['company_id' => $company->id]);

    // MS- First trip
    Trip::create([
        'company_id' => $company->id,
        'driver_id' => $driver->id,
        'vehicle_id' => $vehicle->id,
        'start_time' => now(),
        'end_time' => now()->addHour(),
        'status' => TripStatus::Active,
    ]);

    // MS- Second trip ( starts after the first one ends )
    $trip2 = [
        'company_id' => $company->id,
        'driver_id' => $driver->id,
        'vehicle_id' => $vehicle->id,
        'start_time' => now()->addHours(2),
        'end_time' => now()->addHours(3),
        'status' => TripStatus::Active,
    ];

    expect(Trip::hasOverlap($trip2))->toBeFalse();
});

it('returns only ongoing active trips with activeNow scope', function () {
    $company = Company::factory()->create();
    $driver = Driver::factory()->create(['company_id' => $company->id]);
    $vehicle = Vehicle::factory()->create(['company_id' => $company->id]);

    // MS- Ongoing trip (should be included)
    $trip1 = Trip::create([
        'company_id' => $company->id,
        'driver_id' => $driver->id,
        'vehicle_id' => $vehicle->id,
        'start_time' => now()->subHour(),
        'end_time' => now()->addHour(),
        'status' => TripStatus::Active,
    ]);

    // MS- Future trip (should NOT be included)
    $trip2 = Trip::create([
        'company_id' => $company->id,
        'driver_id' => $driver->id,
        'vehicle_id' => $vehicle->id,
        'start_time' => now()->addHours(2),
        'end_time' => now()->addHours(3),
        'status' => TripStatus::Active,
    ]);

    // MS- Completed trip (should NOT be included)
    $trip3 = Trip::create([
        'company_id' => $company->id,
        'driver_id' => $driver->id,
        'vehicle_id' => $vehicle->id,
        'start_time' => now()->subHours(3),
        'end_time' => now()->subHours(2),
        'status' => TripStatus::Completed,
    ]);

    $activeTrips = Trip::activeNow()->pluck('id')->all();

    expect($activeTrips)->toContain($trip1->id)
        ->not->toContain($trip2->id)
        ->not->toContain($trip3->id);
});

it('returns only completed trips from the current month', function () {
    $company = Company::factory()->create();
    $driver = Driver::factory()->create(['company_id' => $company->id]);
    $vehicle = Vehicle::factory()->create(['company_id' => $company->id]);

    // MS- Trip completed this month 
    $trip1 = Trip::create([
        'company_id' => $company->id,
        'driver_id' => $driver->id,
        'vehicle_id' => $vehicle->id,
        'start_time' => now()->subDays(3),
        'end_time' => now()->subDays(2),
        'status' => TripStatus::Completed,
    ]);

    // MS- Trip completed last month 
    $trip2 = Trip::create([
        'company_id' => $company->id,
        'driver_id' => $driver->id,
        'vehicle_id' => $vehicle->id,
        'start_time' => now()->subMonth()->startOfMonth(),
        'end_time' => now()->subMonth()->startOfMonth()->addDay(),
        'status' => TripStatus::Completed,
    ]);

    // MS- Trip still active 
    $trip3 = Trip::create([
        'company_id' => $company->id,
        'driver_id' => $driver->id,
        'vehicle_id' => $vehicle->id,
        'start_time' => now()->subHour(),
        'end_time' => now()->addHour(),
        'status' => TripStatus::Active,
    ]);

    $completedTrips = Trip::completedThisMonth()->pluck('id')->all();

    expect($completedTrips)->toContain($trip1->id)
        ->not->toContain($trip2->id)
        ->not->toContain($trip3->id);
});

it('returns drivers who are not in active trips as available', function () {
    $company = Company::factory()->create();
    $driver1 = Driver::factory()->create(['company_id' => $company->id]);
    $driver2 = Driver::factory()->create(['company_id' => $company->id]);
    $vehicle = Vehicle::factory()->create(['company_id' => $company->id]);

    // MS- Driver1 is busy with an active trip
    Trip::create([
        'company_id' => $company->id,
        'driver_id' => $driver1->id,
        'vehicle_id' => $vehicle->id,
        'start_time' => now()->subHour(),
        'end_time' => now()->addHour(),
        'status' => TripStatus::Active,
    ]);

    // MS- Driver2 is free (no active trips - scheduled trip only)
    Trip::create([
        'company_id' => $company->id,
        'driver_id' => $driver2->id,
        'vehicle_id' => $vehicle->id,
        'start_time' => now()->addHour(),
        'end_time' => now()->addHours(2),
        'status' => TripStatus::Scheduled,
    ]);

    $availableDrivers = Driver::whereDoesntHave('trips', function ($query) {
        $query->activeNow();
    })->pluck('id')->all();

    expect($availableDrivers)->toContain($driver2->id)   
        ->not->toContain($driver1->id);                 
});

it('returns vehicles that are not in active trips as available', function () {
    $company = Company::factory()->create();
    $driver = Driver::factory()->create(['company_id' => $company->id]);
    $vehicle1 = Vehicle::factory()->create(['company_id' => $company->id]);
    $vehicle2 = Vehicle::factory()->create(['company_id' => $company->id]);

    // MS- Vehicle1 is busy with an active trip
    Trip::create([
        'company_id' => $company->id,
        'driver_id' => $driver->id,
        'vehicle_id' => $vehicle1->id,
        'start_time' => now()->subHour(),
        'end_time' => now()->addHour(),
        'status' => TripStatus::Active,
    ]);

    // MS- Vehicle2 is free (no active trips - scheduled trip only)
    Trip::create([
        'company_id' => $company->id,
        'driver_id' => $driver->id,
        'vehicle_id' => $vehicle2->id,
        'start_time' => now()->addHour(),
        'end_time' => now()->addHours(2),
        'status' => TripStatus::Scheduled,
    ]);

    $availableVehicles = Vehicle::whereDoesntHave('trips', function ($query) {
        $query->activeNow();
    })->pluck('id')->all();

    expect($availableVehicles)->toContain($vehicle2->id)   
        ->not->toContain($vehicle1->id);                 
});




