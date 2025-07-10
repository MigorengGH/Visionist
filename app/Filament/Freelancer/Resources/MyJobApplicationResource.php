<?php

namespace App\Filament\Freelancer\Resources;

use App\Models\Makejob;
use App\Filament\Freelancer\Resources\MyJobApplicationResource\Pages;
use App\Models\Application;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Fieldset;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Joaopaulolndev\FilamentPdfViewer\Infolists\Components\PdfViewerEntry;
use Filament\Infolists\Components\Grid;
use App\Models\State;
use App\Models\District;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Notifications\Notification;

class MyJobApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'My Applications';

    protected static ?string $navigationGroup = 'Job';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('makejob.title')
                    ->label('Job Title')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->makejob->title),
                TextColumn::make('makejob.is_online')
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => $state ? 'Online' : 'Physical')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault:true)
                    ->color(fn ($state) => $state ? 'success' : 'primary'),
                TextColumn::make('makejob.state_id')
                    ->label('State')
                    ->icon('heroicon-o-map')
                    ->color('info')
                    ->searchable()
                    ->sortable()
                    ->toggleable(true)
                    ->toggledHiddenByDefault()
                    ->getStateUsing(fn ($record) => $record->makejob->is_online ? 'Online' : optional(State::find($record->makejob->state_id))->name),
                TextColumn::make('makejob.district_id')
                    ->icon('heroicon-o-map-pin')
                    ->color('primary')
                    ->label('District')
                    ->searchable()
                    ->sortable()
                    ->toggleable(true)
                    ->toggledHiddenByDefault()
                    ->getStateUsing(fn ($record) => $record->makejob->is_online ? '-' : optional(District::find($record->makejob->district_id))->name),
                TextColumn::make('makejob.tags')
                    ->label('Tags')
                    ->badge()
                    ->toggleable(true)
                    ->toggledHiddenByDefault()
                    ->separator(','),
                TextColumn::make('makejob.description')
                    ->label('Job Description')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->makejob->description)
                    ->toggleable(true)
                    ->toggledHiddenByDefault()
                    ->searchable(),
                TextColumn::make('proposed_price')
                    ->money('MYR')
                    ->sortable(),
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'accepted',
                        'danger' => 'rejected',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Applied At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                    ])
                    ->label('Application Status'),
                Tables\Filters\SelectFilter::make('makejob.is_online')
                    ->label('Job Type')
                    ->options([
                        '1' => 'Online',
                        '0' => 'Physical',
                    ]),
                Tables\Filters\SelectFilter::make('state_id')
                    ->options(State::pluck('name', 'id')->toArray())
                    ->label('State'),
                Tables\Filters\SelectFilter::make('district_id')
                    ->options(District::pluck('name', 'id')->toArray())
                    ->label('District'),
            ])
            ->actions([
                Action::make('view_contract')
                    ->label('View Contract')
                    ->icon('heroicon-o-document-text')
                    ->color('success')
                    ->url(fn (Application $record) => route('filament.freelancer.resources.myjobs.view-contract', ['record' => $record->makejob_id]))
                    ->visible(fn (Application $record) => $record->status === 'accepted'),
                Action::make('downloadContract')
                    ->label('Download Contract')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function (Application $record) {
                        if ($record->status !== 'accepted') {
                            Notification::make()
                                ->title('Only accepted applications can download contracts')
                                ->danger()
                                ->send();
                            return;
                        }

                        $pdf = Pdf::loadView('contracts.job-contract', [
                            'job' => $record->makejob,
                            'client' => $record->makejob->user,
                            'freelancer' => $record->user,
                            'application' => $record,
                        ]);

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, "contract-{$record->makejob_id}.pdf");
                    })
                    ->visible(fn (Application $record) => $record->status === 'accepted'),
                ViewAction::make()
                    ->label('View Details')
                    ->icon('heroicon-o-eye'),
            ])
            ->bulkActions([])
            ->modifyQueryUsing(fn ($query) => $query->where('user_id', Auth::id()))
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Job Details')
                    ->description('Information about the job you applied for')
                    ->icon('heroicon-o-briefcase')
                    ->schema([
                        Grid::make(2)
                            ->schema([

                                TextEntry::make('makejob.title')
                                    ->label('')
                                    ->iconPosition(IconPosition::Before)
                                    ->weight(FontWeight::Bold)
                                    ->columnSpan(2),
                                TextEntry::make('makejob.state_id')
                                    ->label('State')
                                    ->icon('heroicon-o-map')
                                    ->color('info')
                                    ->getStateUsing(fn ($record) => $record->makejob && $record->makejob->is_online ? 'Online' : (optional(\App\Models\State::find($record->makejob->state_id))->name ?? ''))
                                    ->columnSpan(1),
                                TextEntry::make('makejob.district_id')
                                    ->label('District')
                                    ->icon('heroicon-o-map-pin')
                                    ->color('primary')
                                    ->getStateUsing(fn ($record) => $record->makejob && $record->makejob->is_online ? 'Online' : (optional(\App\Models\District::find($record->makejob->district_id))->name ?? ''))
                                    ->columnSpan(1),
                                TextEntry::make('makejob.tags')
                                    ->label('Tags')
                                    ->badge()
                                    ->separator(',')
                                    ->columnSpan(2),
                                TextEntry::make('makejob.description')
                                    ->label('Description')
                                    ->markdown()
                                    ->columnSpan(2),
                                TextEntry::make('makejob.budget')
                                    ->money('MYR')
                                    ->label('Budget')
                                    ->icon('heroicon-o-currency-dollar')
                                    ->iconPosition(IconPosition::Before)
                                    ->color('gray'),
                                TextEntry::make('proposed_price')
                                    ->money('MYR')
                                    ->label('Your Proposal')
                                    ->icon('heroicon-o-currency-dollar')
                                    ->iconPosition(IconPosition::Before)
                                    ->color('primary'),
                                TextEntry::make('cover_letter')
                                    ->label('Cover Letter')
                                    ->markdown()
                                    ->color('primary')
                                    ->columnSpanFull(),
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'pending' => 'warning',
                                        'accepted' => 'success',
                                        'rejected' => 'danger',
                                    })
                                    ->columnSpan(2),
                                TextEntry::make('makejob.user.name')
                                    ->label('Client')
                                    ->icon('heroicon-o-user')
                                    ->iconPosition(IconPosition::Before)
                                    ->color('gray'),
                                TextEntry::make('created_at')
                                    ->label('Applied At')
                                    ->dateTime()
                                    ->icon('heroicon-o-calendar')
                                    ->iconPosition(IconPosition::Before)
                                    ->color('gray'),
                            ]),
                    ])->columnSpan(4),


                Section::make('My Supporting Documents')
                    ->description('View your submitted supporting documents')
                    ->icon('heroicon-o-document')
                    ->schema([
                        PdfViewerEntry::make('supporting_documents')
                            ->label('')
                            ->minHeight('50svh')
                            ->fileUrl(fn (Application $record) => $record->supporting_documents ? asset('storage/' . $record->supporting_documents) : null)
                            ->columnSpanFull(),
                    ])->columnSpan(2),

            ])->columns(6);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMyJobApplications::route('/'),
            'view' => Pages\ViewMyJobApplication::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
