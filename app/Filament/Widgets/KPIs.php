<?php

namespace App\Filament\Widgets;

use App\Models\Driver;
use App\Models\Trip;
use App\Models\Vehicle;
use Filament\Actions\Action;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class KPIs extends BaseWidget
{
    protected static ?string $pollingInterval = '30s'; 
    protected int | string | array $columnSpan = 'full';

    protected function getHeading(): ?string
    {
        return 'Analytics moataz';
    }

    protected function getDescription(): ?string
    {
        return 'An overview of some analytics.';
    }
    protected function getStats(): array
    {
        return [
            // MS- Active Trips Widget
            Stat::make(
                'Active Trips',
                Cache::remember('active_trips_count', 300, fn() => Trip::activeNow()->count())
            )
                ->color('primary')
                ->description('Currently running trips')
                ->descriptionIcon('heroicon-o-truck')
                ->chart([1, 3, 5, 2, 4, 6])
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-105 transition',
                ]),

            // MS- Available Drivers Widget
            Stat::make(
                'Available Drivers',
                Cache::remember('available_drivers', 300, function () {
                    return Driver::whereDoesntHave('trips', function (Builder $query) {
                        $query->activeNow();
                    })->count();
                })
            )
                ->color('warning')
                ->description('Drivers not on a trip')
                ->chart([1, 3, 5, 2, 4, 6])
                ->descriptionIcon('heroicon-o-user'),

            // MS- Available Vehicles Widget
            Stat::make(
                'Available Vehicles',
                Cache::remember('available_vehicles', 300, function () {
                    return Vehicle::whereDoesntHave('trips', function (Builder $query) {
                        $query->activeNow();
                    })->count();
                })
            )
                ->color('info')
                ->description('Vehicles ready to use')
                ->chart([1, 3, 5, 2, 4, 6])
                ->descriptionIcon('heroicon-o-truck'),

            // MS- Completed Trips (This Month) Widget
            Stat::make(
                'Completed Trips',
                Cache::remember('completed_trips_month', 300, fn() => Trip::completedThisMonth()->count())
            )
                ->color('success')
                ->chart([1, 3, 5, 2, 4, 6])
                ->description('Trips completed this month')
                ->descriptionIcon('heroicon-o-check-circle'),


        ];
    }


}
