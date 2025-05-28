<?php

namespace App\Filament\Freelancer\Resources\ArtworkResource\Pages;

use App\Filament\Freelancer\Resources\ArtworkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArtwork extends EditRecord
{
    protected static string $resource = ArtworkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
