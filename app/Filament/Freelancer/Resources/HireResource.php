<?php

namespace App\Filament\Freelancer\Resources;

use Filament\Forms;
use App\Models\Hire;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Freelancer\Resources\HireResource\Pages;

class HireResource extends Resource
{
    protected static ?string $model = Hire::class;

    protected static ?string $navigationIcon = 'heroicon-o-fire';

    protected static ?string $navigationLabel = 'Hires Management';

    protected static ?string $navigationGroup = 'Hiring Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('MYR')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_online')
                    ->boolean(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'accepted' => 'success',
                        'rejected' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('view_contract')
                    ->label('View Contract')
                    ->icon('heroicon-o-document-text')
                    ->url(fn (Hire $record): string => route('filament.freelancer.resources.hires.view-contract', $record))
                    ->visible(fn (Hire $record): bool => $record->status === 'accepted'),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                //]),
            ]);
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
            'index' => Pages\ListHires::route('/'),
            'create' => Pages\CreateHire::route('/create'),
            'view' => Pages\ViewHire::route('/{record}'),
            'view-contract' => Pages\ViewContract::route('/{record}/contract'),
        ];
    }

    //hide create button
    public static function canCreate(): bool
    {
        return false;
    }
}
