<?php

namespace App\Filament\Freelancer\Resources\UserProfileResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TagsColumn;

class ArtworksRelationManager extends RelationManager
{
    protected static string $relationship = 'artworks';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->size(50),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('likes_count')
                    ->label('Likes')
                    ->icon('heroicon-o-star')
                    ->iconColor('warning')
                    ->sortable(),
                TagsColumn::make('tags')
                    ->limit(3)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View Artwork')
                    ->icon('heroicon-m-eye')
                    ->url(fn ($record) => route('filament.freelancer.resources.artworks.view', ['record' => $record])),
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('created_at', 'desc');
    }
}
