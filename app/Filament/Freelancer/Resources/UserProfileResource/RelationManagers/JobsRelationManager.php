<?php

namespace App\Filament\Freelancer\Resources\UserProfileResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;

class JobsRelationManager extends RelationManager
{
    protected static string $relationship = 'makejobs';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $title = 'Open Jobs';

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
        ->emptyStateHeading('No job post by this user')
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('budget')
                    ->money('MYR')
                    ->sortable(),
                TextColumn::make('applications_count')
                    ->label('Applications')
                    ->counts('applications')
                    ->sortable(),
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
                    ->label('View Job')
                    ->icon('heroicon-m-eye')
                    ->url(fn ($record) => route('filament.freelancer.resources.searchjobs.view', ['record' => $record])),
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('created_at', 'desc');
    }

    protected function getTableQuery(): Builder
    {
        return $this->getOwnerRecord()->makejobs()->getQuery()->where('status', 'open');
    }
}
