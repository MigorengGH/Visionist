<?php

namespace App\Filament\Freelancer\Resources\ArtworkResource\Pages;

use App\Filament\Freelancer\Resources\ArtworkResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewArtwork extends ViewRecord
{
    protected static string $resource = ArtworkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
