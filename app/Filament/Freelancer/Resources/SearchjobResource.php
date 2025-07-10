<?php

namespace App\Filament\Freelancer\Resources;

use Filament\Tables;
use App\Models\Makejob;
use Filament\Tables\Table;
use App\Models\Application;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Freelancer\Resources\SearchjobResource\Pages;
use App\Filament\Freelancer\Resources\SearchjobResource\RelationManagers\OtherJobsRelationManager;
use App\Models\State;
use App\Models\District;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;

class SearchjobResource extends Resource
{

    protected static ?string  $breadcrumb = 'Search Jobs';
    protected static ?string $navigationLabel = 'Search Jobs';
    protected static ?string $model = Makejob::class;
    protected static ?string $navigationGroup = 'Job';
    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    public static function table(Table $table): Table
    {
        return $table
        ->emptyStateHeading('No job available')
            ->columns([

                ImageColumn::make('user.avatar')
                    ->label('Avatar')
                    ->circular()
                    ->toggleable(isToggledHiddenByDefault:true)
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->user->name) . '&color=7F9CF5&background=EBF4FF'),
                TextColumn::make('user.name')
                    ->label('Posted By')
                    ->toggleable(isToggledHiddenByDefault:true)
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('is_online')
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => $state ? 'Online' : 'Physical')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault:true)
                    ->color(fn ($state) => $state ? 'success' : 'primary'),
                TextColumn::make('state_id')
                    ->label('State')
                    ->icon('heroicon-o-map')
                    ->color('info')
                    ->toggleable(isToggledHiddenByDefault:true)
                    ->getStateUsing(fn ($record) => $record->is_online ? 'Online' : (optional(\App\Models\State::find($record->state_id))->name ?? '')),
                TextColumn::make('district_id')
                    ->label('District')
                    ->icon('heroicon-o-map-pin')
                    ->color('primary')
                    ->getStateUsing(fn ($record) => $record->is_online ? 'Online' : (optional(\App\Models\District::find($record->district_id))->name ?? '')),
                TextColumn::make('tags')
                    ->label('Tags')
                    ->badge()
                    ->searchable()
                    ->color('success')
                    ->separator(',')
                    ->tooltip(fn ($record) => is_array($record->tags) ? implode(', ', $record->tags) : $record->tags)
                    ->limitList(3)
                    ->sortable(),
                TextColumn::make('budget')
                    ->money('MYR')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('budget')
                    ->form([
                        TextInput::make('min_budget')
                            ->numeric()
                            ->prefix('MYR'),
                        TextInput::make('max_budget')
                            ->numeric()
                            ->prefix('MYR'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['min_budget'],
                                fn (Builder $query, $min): Builder => $query->where('budget', '>=', $min),
                            )
                            ->when(
                                $data['max_budget'],
                                fn (Builder $query, $max): Builder => $query->where('budget', '<=', $max),
                            );
                    }),
                SelectFilter::make('is_online')
                    ->label('Job Type')
                    ->options([
                        '1' => 'Online',
                        '0' => 'Physical',
                    ]),
            ])
            ->actions([
                Action::make('view')
                    ->label('Details')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn (Makejob $record): string => route('filament.freelancer.resources.searchjobs.view', ['record' => $record])),
                Action::make('apply')
                    ->form([
                        TextInput::make('proposed_price')
                            ->numeric()
                            ->prefix('MYR')
                            ->required(),
                        RichEditor::make('cover_letter')
                            ->required(),
                        FileUpload::make('cv')
                            ->label('CV/Resume')
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->maxSize(10240)
                            ->required(),
                    ])
                    ->action(function (Makejob $record, array $data) {
                        $record->applications()->create([
                            'user_id' => Auth::id(),
                            'proposed_price' => $data['proposed_price'],
                            'cover_letter' => $data['cover_letter'],
                            'supporting_documents' => $data['supporting_documents'],
                            'status' => 'pending',
                        ]);
                    })
                    ->visible(fn ($record) =>
                        $record && $record->status === 'pending'
                    )
                    ->requiresConfirmation(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSearchjobs::route('/'),
            'view' => Pages\ViewSearchjob::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('status', 'open');
    }

    public static function getRelations(): array
    {
        return [
            OtherJobsRelationManager::class,
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
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
                                    ->icon('heroicon-o-tag')
                                    ->formatStateUsing(fn ($state) => implode(', ', $state)),
                            ]),
                    ]),
            ]);
    }
}
