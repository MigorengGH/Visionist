<?php

namespace App\Filament\Freelancer\Resources\ArtworkResource\Pages;

use App\Filament\Freelancer\Resources\ArtworkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArtworks extends ListRecords
{
    protected static string $resource = ArtworkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
