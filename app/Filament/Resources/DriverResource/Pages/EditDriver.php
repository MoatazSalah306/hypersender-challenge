<?php

namespace App\Filament\Resources\DriverResource\Pages;

use App\Filament\Resources\DriverResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditDriver extends EditRecord
{
    protected static string $resource = DriverResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->successNotificationTitle('Driver deleted successfully!'),
        ];
    }

      protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Driver updated successfully!')
            ->color('success')
            ->success();
    }
}
