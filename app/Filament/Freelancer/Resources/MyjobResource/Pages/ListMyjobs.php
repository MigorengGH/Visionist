<?php

namespace App\Filament\Freelancer\Resources\MyjobResource\Pages;

use App\Filament\Freelancer\Resources\MyjobResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use PhpParser\Node\Stmt\Label;

class ListMyjobs extends ListRecords
{
    protected static string $resource = MyjobResource::class;

    public function getTitle(): string
    {
        return 'My Jobs';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Post a Job'),
        ];

        //title header action
        // Actions\CreateAction::make(),
    }
}
