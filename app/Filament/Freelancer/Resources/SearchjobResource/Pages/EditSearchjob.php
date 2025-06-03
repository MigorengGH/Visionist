<?php

namespace App\Filament\Freelancer\Resources\SearchjobResource\Pages;

use App\Filament\Freelancer\Resources\SearchjobResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSearchjob extends EditRecord
{
    protected static string $resource = SearchjobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
