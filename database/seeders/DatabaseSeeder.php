<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Driver;
use App\Models\Trip;
use App\Models\User;
use App\Models\Vehicle;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $companies = Company::factory(3)->create();

        foreach ($companies as $company) {
            Driver::factory(5)->create(['company_id' => $company->id]);
            Vehicle::factory(5)->create(['company_id' => $company->id]);
            Trip::factory(10)->create(['company_id' => $company->id]);
        }
    }
}
