<?php

namespace App\Filament\Freelancer\Resources;

use App\Models\User;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use App\Filament\Freelancer\Resources\UserProfileResource\Pages;
use App\Filament\Freelancer\Resources\UserProfileResource\RelationManagers;
use RelationManagers\HiresRelationManager;

class UserProfileResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Find Users';

    protected static ?string $modelLabel = 'User Profile';

    protected static ?string $slug = 'user-profiles';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label('Profile Picture')
                    ->circular()
                    ->size(120)
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&color=7F9CF5&background=EBF4FF'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('artworks_count')
                    ->label('Artworks')
                    ->counts('artworks')
                    ->sortable(),
                TextColumn::make('makejobs_count')
                    ->label('Jobs Posted')
                    ->counts('makejobs')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                \Filament\Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Profile Information')
                    ->schema([
                        ImageEntry::make('avatar')
                            ->label('Profile Picture')
                            ->circular()
                            ->size(120)
                            ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&color=7F9CF5&background=EBF4FF'),
                        TextEntry::make('name')
                            ->label('Name')
                            ->size(\Filament\Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight(\Filament\Support\Enums\FontWeight::Bold),
                        TextEntry::make('email')
                            ->label('Email')
                            ->icon('heroicon-m-envelope'),
                        TextEntry::make('phone')
                            ->label('Phone')
                            ->icon('heroicon-m-phone'),
                        TextEntry::make('aboutme')
                            ->label('About Me')
                            ->markdown()
                            ->columnSpanFull(),
                        TextEntry::make('tags')
                            ->label('Tags')
                            ->badge()
                            ->separator(',')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Social Media')
                    ->schema([
                        TextEntry::make('instagram')
                            ->label('Instagram')
                            ->url(fn ($record) => $record->instagram)
                            ->openUrlInNewTab()
                            ->icon('heroicon-m-camera'),
                        TextEntry::make('youtube')
                            ->label('YouTube')
                            ->url(fn ($record) => $record->youtube)
                            ->openUrlInNewTab()
                            ->icon('heroicon-m-play'),
                    ])
                    ->columns(2),

                Section::make('Certificates')
                    ->schema([
                        TextEntry::make('certificate_1')
                            ->label('Certificate 1')
                            ->icon('heroicon-m-academic-cap'),
                        TextEntry::make('certificate_2')
                            ->label('Certificate 2')
                            ->icon('heroicon-m-academic-cap'),
                        TextEntry::make('certificate_3')
                            ->label('Certificate 3')
                            ->icon('heroicon-m-academic-cap'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ArtworksRelationManager::class,
            RelationManagers\JobsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserProfiles::route('/'),
            'view' => Pages\ViewUserProfile::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->where('isAdmin', false);
    }

}
