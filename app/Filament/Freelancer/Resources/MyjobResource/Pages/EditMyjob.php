<?php

namespace App\Filament\Freelancer\Resources\MyjobResource\Pages;

use App\Filament\Freelancer\Resources\MyjobResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMyjob extends EditRecord
{
    protected static string $resource = MyjobResource::class;

    public function getTitle(): string
    {
        return 'My Jobs';
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->visible(fn ($record) =>
                    $record->status === 'open' &&
                    !$record->applications()->exists() &&
                    !$record->applications()->where('status', 'accepted')->exists()
                ),
        ];
    }
}
