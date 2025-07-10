<?php

namespace App\Filament\Freelancer\Resources\SearchjobResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Makejob;

class OtherJobsRelationManager extends RelationManager
{
    protected static string $relationship = 'user';

    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $title = 'Other Jobs by This User';

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
        ->emptyStateHeading('No other jobs from this user')
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('budget')
                    ->money('MYR')
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
                Action::make('view')
                    ->url(fn ($record): string => route('filament.freelancer.resources.searchjobs.view', ['record' => $record])),
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(false);
    }

    protected function getTableQuery(): Builder
    {
        return Makejob::query()
            ->where('user_id', $this->ownerRecord->user_id)
            ->where('id', '!=', $this->ownerRecord->id)
            ->where('status', 'open');
    }
}
