<?php

namespace App\Filament\Freelancer\Resources\SearchjobResource\Pages;

use App\Filament\Freelancer\Resources\SearchjobResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Auth;
use App\Models\Makejob;
use Filament\Actions\Action as ActionsAction;
use App\Models\State;
use App\Models\District;

class ViewSearchjob extends ViewRecord
{
    public function getTitle(): string
    {
        return 'Job Details';
    }
    protected static string $resource = SearchjobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionsAction::make('apply')
                ->form([
                    TextInput::make('proposed_price')
                        ->numeric()
                        ->prefix('MYR')
                        ->required(),
                    TextInput::make('cover_letter')
                        ->label('Description/Cover Letter')
                        ->required(),
                    FileUpload::make('cv')
                        ->label('Supporting Documents')
                        ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                        ->maxSize(10240),
                ])
                ->action(function (Makejob $record, array $data) {
                    $record->applications()->create([
                        'user_id' => Auth::id(),
                        'proposed_price' => $data['proposed_price'],
                        'cover_letter' => $data['cover_letter'],
                        'supporting_documents' => $data['cv'],
                        'status' => 'pending',
                    ]);
                })
                ->visible(fn ($record) =>
                    $record->status === 'open' &&
                    $record->user_id !== Auth::id() &&
                    !$record->applications()->where('user_id', Auth::id())->exists()
                )
                ->requiresConfirmation(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Job Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('title')
                                    ->label('Job Title')
                                    ->icon('heroicon-o-briefcase'),
                                TextEntry::make('budget')
                                    ->money('MYR')
                                    ->label('Budget')
                                    ->icon('heroicon-o-currency-dollar'),
                                TextEntry::make('state_id')
                                    ->label('State')
                                    ->icon('heroicon-o-map')
                                    ->color('info')
                                    ->getStateUsing(fn ($record) => $record->is_online ? 'Online' : (optional(\App\Models\State::find($record->state_id))->name ?? '')),
                                TextEntry::make('district_id')
                                    ->label('District')
                                    ->icon('heroicon-o-map-pin')
                                    ->color('primary')
                                    ->getStateUsing(fn ($record) => $record->is_online ? 'Online' : (optional(\App\Models\District::find($record->district_id))->name ?? '')),
                                TextEntry::make('tags')
                                    ->label('Tags')
                                    ->badge()
                                    ->color('success')
                                    ->separator(',')
                                    ->columnSpan(2),
                                TextEntry::make('user.name')
                                    ->label('Posted By')
                                    ->icon('heroicon-o-user'),
                                TextEntry::make('created_at')
                                    ->dateTime()
                                    ->label('Posted On')
                                    ->icon('heroicon-o-calendar'),
                            ]),
                        TextEntry::make('description')
                            ->markdown()
                            ->columnSpanFull(),
                    ]),
                // Section::make('Applications')
                //     ->schema([
                //         RepeatableEntry::make('applications')
                //             ->schema([
                //                 Grid::make(2)
                //                     ->schema([
                //                         TextEntry::make('user.name')
                //                             ->label('Applicant'),
                //                         TextEntry::make('proposed_price')
                //                             ->money('MYR')
                //                             ->label('Proposed Price'),
                //                         TextEntry::make('status')
                //                             ->badge()
                //                             ->color(fn (string $state): string => match ($state) {
                //                                 'pending' => 'warning',
                //                                 'accepted' => 'success',
                //                                 'rejected' => 'danger',
                //                             }),
                //                         TextEntry::make('created_at')
                //                             ->dateTime()
                //                             ->label('Applied On'),
                //                     ]),
                //                 TextEntry::make('cover_letter')
                //                     ->markdown()
                //                     ->columnSpanFull(),
                //                 Actions::make([
                //                     Action::make('view_cv')
                //                         ->url(fn ($record) => $record->supporting_documents)
                //                         ->openUrlInNewTab()
                //                         ->label('Supporting Documents'),
                //                 ]),
                //             ])
                //             ->columns(1),
                //     ])
                //     ->visible(fn ($record) => $record->user_id === Auth::id()),
            ]);
    }
}
