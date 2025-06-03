<?php

namespace App\Filament\Admin\Resources\WorkshopResource\Pages;

use Filament\Actions;
use App\Models\Workshop;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\WorkshopResource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords\Tab;

class ListWorkshops extends ListRecords
{
    protected static string $resource = WorkshopResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
    //tab for paid or free based on price
    return [
        'All' => Tab::make()->badge(Workshop::count()),

        'Free' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('price', 0))
            ->badge(Workshop::where('price', 0)->count()),
        'Paid' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('price', '>', 0))
            ->badge(Workshop::where('price', '>', 0)->count()),
        ];
    }
}
