<?php

namespace App\Filament\Freelancer\Resources\HireResource\Pages;

use App\Filament\Freelancer\Resources\HireResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHires extends ListRecords
{
    protected static string $resource = HireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
