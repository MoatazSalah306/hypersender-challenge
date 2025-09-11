<?php

namespace App\Filament\Resources\TripResource\Pages;

use App\Filament\Resources\TripResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateTrip extends CreateRecord
{
    protected static string $resource = TripResource::class;

     protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Trip created successfully!')
            ->color('success')
            ->success();
    }
}
