<?php

namespace App\Filament\Freelancer\Resources;

use Filament\Tables;
use App\Models\State;
use App\Models\District;
use App\Models\Location;
use App\Models\Workshop;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use App\Filament\Freelancer\Resources\WorkshopResource\Pages;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\Layout\Split;

class WorkshopResource extends Resource
{
    protected static ?string $model = Workshop::class;

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    protected static ?string $navigationLabel = 'Workshops';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Workshop Images')
                    ->icon('heroicon-o-photo')
                    ->collapsible()
                    ->schema([
                        ImageEntry::make('image')
                            ->label('')
                            ->stacked()
                            ->size(300)
                            ->extraImgAttributes(['class' => 'object-cover w-full h-full rounded-lg'])
                            ->columnSpanFull(),
                    ]),
                Section::make('Workshop Details')
                    ->description('Complete information about this workshop')
                    ->icon('heroicon-o-cube-transparent')
                    ->collapsible()
                    ->schema([
                        TextEntry::make('name')
                            ->label('Title')
                            ->icon('heroicon-o-cube-transparent')
                            ->iconColor('primary')
                            ->size(TextEntry\TextEntrySize::Large)
                            ->weight(FontWeight::Bold),
                        TextEntry::make('description')
                            ->label('Description')
                            ->markdown()
                            ->columnSpanFull(),
                        TextEntry::make('start_date')
                            ->label('Date & Time')
                            ->icon('heroicon-o-calendar')
                            ->dateTime('M d, Y -- H:i')
                            ->badge()
                            ->color('info'),
                        TextEntry::make('district.name')
                            ->label('Location')
                            ->icon('heroicon-o-map-pin')
                            ->formatStateUsing(fn ($state, $record) => $record->district->state->name . ' - ' . $state)
                            ->badge()
                            ->color('success'),
                        TextEntry::make('price')
                            ->label('Price')
                            ->icon('heroicon-o-currency-dollar')
                            ->formatStateUsing(fn ($state) => $state == 0.00 ? 'FREE' : 'RM ' . number_format($state, 2))
                            ->badge()
                            ->color(fn ($state) => $state == 0.00 ? 'success' : 'warning'),
                        TextEntry::make('tags')
                            ->label('Tags')
                            ->icon('heroicon-o-tag')
                            ->badge()
                            ->color('gray')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'sm' => 1,
                'md' => 2,
                'lg' => 2,
                'xl' => 3,
            ])
            ->columns([
                Grid::make()
                    ->schema([
                        ImageColumn::make('image')
                            ->label('')
                            ->limit(1)
                            ->size(120)
                            ->extraImgAttributes(['class' => 'object-cover w-full h-full rounded-lg']),
                        Stack::make([
                            TextColumn::make('name')
                                ->label('Title')
                                ->searchable()
                                ->weight(FontWeight::Bold)
                                ->size(TextColumn\TextColumnSize::Large)
                                ->limit(20)
                                ->tooltip(function (TextColumn $column): ?string {
                                    $state = $column->getState();
                                    if (strlen($state) <= 20) {
                                        return null;
                                    }
                                    return $state;
                                }),
                            TextColumn::make('start_date')
                                ->label('Date')
                                ->dateTime('M d, Y')
                                ->searchable()
                                ->sortable()
                                ->icon('heroicon-o-calendar')
                                ->iconPosition('before'),
                            TextColumn::make('district.name')
                                ->label('Location')
                                ->searchable()
                                ->icon('heroicon-o-map-pin')
                                ->iconPosition('before')
                                ->limit(15),
                            TextColumn::make('price')
                                ->label('Price')
                                ->sortable()
                                ->searchable()
                                ->formatStateUsing(fn ($state) => $state == 0.00 ? 'FREE' : 'RM ' . number_format($state, 2))
                                ->badge()
                                ->color(fn ($state) => $state == 0.00 ? 'success' : 'warning')
                                ->icon('heroicon-o-currency-dollar')
                                ->iconPosition('before'),
                        ])->space(1),
                    ])
                    ->columns([
                        'sm' => 1,
                        'md' => 2,
                    ])
                    ->extraAttributes(['class' => 'p-3 gap-3 rounded-lg border border-gray-200 dark:border-gray-700'])
                    ->columnSpanFull(),
            ])
            ->filters([
                SelectFilter::make('state')
                    ->label('State')
                    ->relationship('state', 'name')
                    ->searchable()
                    ->preload()
                    ->columnSpan(1),
                Filter::make('start_date')
                    ->label('Date')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('start_date')
                            ->label('Available From')
                            ->native(false)
                            ->placeholder('Select a date')
                            ->displayFormat('M d, Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_date'],
                                fn (Builder $query): Builder => $query->whereDate('start_date', '>=', $data['start_date']),
                            );
                    })
                    ->columnSpan(1),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(2)
            ->searchable()
            ->searchPlaceholder('Search workshops...')
            ->actions([
                // Tables\Actions\ViewAction::make()
                //     ->label('View Details')
                //     ->icon('heroicon-o-eye')
                //     ->color('gray'),
            ])
            ->bulkActions([])
            ->defaultSort('start_date', 'asc')
            ->paginated([12, 24, 36, 48]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkshops::route('/'),
            'view' => Pages\ViewWorkshop::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('publish', true)->count();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('publish', true);
    }
}
