<?php

namespace App\Filament\Admin\Resources\WorkshopResource\Pages;


use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Admin\Resources\WorkshopResource;

class CreateWorkshop extends CreateRecord
{
    protected static string $resource = WorkshopResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
        ->title('Created successfully')
        ->success()
        ->body('The workshop has been created successfully.')
        ->send();

    }
}
