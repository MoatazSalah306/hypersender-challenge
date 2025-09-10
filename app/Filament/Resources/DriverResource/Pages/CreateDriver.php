<?php

namespace App\Filament\Resources\DriverResource\Pages;

use App\Filament\Resources\DriverResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateDriver extends CreateRecord
{
    protected static string $resource = DriverResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Driver created successfully!')
            ->color('success')
            ->success();
    }
}
