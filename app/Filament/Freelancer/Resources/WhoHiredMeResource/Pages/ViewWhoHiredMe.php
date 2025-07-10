<?php

namespace App\Filament\Freelancer\Resources\WhoHiredMeResource\Pages;

use App\Filament\Freelancer\Resources\WhoHiredMeResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Actions\Action;

class ViewWhoHiredMe extends ViewRecord
{
    protected static string $resource = WhoHiredMeResource::class;

    //button to view contract with icon and label
    public function getHeaderActions(): array
    {
        return [
            Action::make('view_contract')
                ->label('View Contract')
                ->icon('heroicon-o-document-text')
                ->url(route('filament.freelancer.resources.who-hired-me.view-contract', $this->record))
                ->visible(fn () => $this->record->status === 'accepted'),
        ];
    }
    public function getInfolist(string $name): ?Infolist
    {
        return Infolist::make()
            ->record($this->record)
            ->columns(2)
            ->schema([
                Section::make('Job Details')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('title')->label('Title')->weight('bold'),
                        TextEntry::make('price')->label('Price')->formatStateUsing(fn ($state) => 'MYR ' . number_format($state, 2)),
                        TextEntry::make('status')->label('Status')->badge(),
                        TextEntry::make('is_online')->label('Online Job')->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No')->badge(fn ($state) => $state ? 'success' : 'gray'),
                        TextEntry::make('description')->label('Description')->html()->columnSpanFull(),
                    ]),
                Section::make('Location')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('display_state')->label('State'),
                        TextEntry::make('display_district')->label('District'),
                    ])
                    ->visible(fn () => !$this->record->is_online),
                Section::make('Client')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('client.name')->label('Client Name'),
                        TextEntry::make('created_at')->label('Created At')->dateTime(),
                    ]),
            ]);
    }
}
