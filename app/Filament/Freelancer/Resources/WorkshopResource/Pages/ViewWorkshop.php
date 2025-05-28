<?php

namespace App\Filament\Freelancer\Resources\WorkshopResource\Pages;

use App\Filament\Freelancer\Resources\WorkshopResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWorkshop extends ViewRecord
{
    protected static string $resource = WorkshopResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
