<?php

namespace App\Filament\Freelancer\Resources\SearchjobResource\Pages;

use App\Filament\Freelancer\Resources\SearchjobResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSearchjobs extends ListRecords
{
    protected static string $resource = SearchjobResource::class;


    public function getTitle(): string
    {
        return 'Search Jobs';
    }
    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }
}
