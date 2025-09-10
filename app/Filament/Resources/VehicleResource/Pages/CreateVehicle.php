<?php

namespace App\Filament\Resources\VehicleResource\Pages;

use App\Filament\Resources\VehicleResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateVehicle extends CreateRecord
{
    protected static string $resource = VehicleResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Vehicle created successfully!')
            ->color('success')
            ->success();
    }
}
