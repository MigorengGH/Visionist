<?php

namespace App\Filament\Freelancer\Resources\HireResource\Pages;

use App\Filament\Freelancer\Resources\HireResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Illuminate\Support\Facades\Auth;

class ViewHire extends ViewRecord
{
    protected static string $resource = HireResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Actions\Action::make('view_contract')
                ->label('View Contract')
                ->icon('heroicon-o-document-text')
                ->url(fn () => route('filament.freelancer.resources.hires.view-contract', $this->record))
                ->visible(fn () => $this->record->status === 'accepted'),
        ];
    }

    public function getInfolist(string $name): ?Infolist
    {
        $isClient = $this->record->client_id === Auth::id();

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
                Section::make($isClient ? 'Freelancer' : 'Client')
                    ->columns(2)
                    ->schema([
                        TextEntry::make($isClient ? 'freelancer.name' : 'client.name')
                            ->label($isClient ? 'Freelancer Name' : 'Client Name'),
                        TextEntry::make('created_at')->label('Created At')->dateTime(),
                    ]),
            ]);
    }
}
