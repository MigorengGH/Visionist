<?php

namespace App\Filament\Freelancer\Resources\MyjobResource\Pages;

use App\Filament\Freelancer\Resources\MyjobResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMyjob extends ViewRecord
{
    protected static string $resource = MyjobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
