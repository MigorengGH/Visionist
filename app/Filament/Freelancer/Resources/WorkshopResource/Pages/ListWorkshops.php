<?php

namespace App\Filament\Freelancer\Resources\WorkshopResource\Pages;

use App\Filament\Freelancer\Resources\WorkshopResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Models\Workshop;
class ListWorkshops extends ListRecords
{
    protected static string $resource = WorkshopResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
    public function getTabs(): array
    {
    //tab for paid or free based on price and only shown published workshops
    return [
        'All' => Tab::make()->badge(Workshop::where('publish', true)->count()),

        'Free' => Tab::make()
        //only shown published workshops
            ->modifyQueryUsing(fn (Builder $query) => $query->where('price', 0)->where('publish', true))
            ->badge(Workshop::where('price', 0)->where('publish', true)->where('publish', true)->count()),
        'Paid' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('price', '>', 0)->where('publish', true))
            ->badge(Workshop::where('price', '>', 0)->where('publish', true)->count()),
        ];
    }
}

