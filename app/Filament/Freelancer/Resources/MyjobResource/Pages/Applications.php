<?php

namespace App\Filament\Freelancer\Resources\MyjobResource\Pages;

use App\Filament\Freelancer\Resources\MyjobResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;

class Applications extends ViewRecord implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string $resource = MyjobResource::class;

    protected static string $view = 'filament.resources.makejob-resource.pages.applications';

    public function getTitle(): string
    {
        return 'Job Applications';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getRecord()->applications()->getQuery())
            ->columns([
                TextColumn::make('user.name')
                    ->label('Applicant'),
                TextColumn::make('proposed_price')
                    ->money('MYR'),
                TextColumn::make('cover_letter')
                    ->limit(50),
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'accepted',
                        'danger' => 'rejected',
                    ]),
                TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->actions([
                Action::make('accept')
                    ->action(function ($record) {
                        // Accept this application
                        $record->update(['status' => 'accepted']);

                        // Get the job and reject all other applications
                        $makejob = $record->makejob()->first();
                        $makejob->applications()
                            ->where('id', '!=', $record->id)
                            ->where('status', '!=', 'rejected')
                            ->update(['status' => 'rejected']);

                        // Close the job
                        $makejob->update(['status' => 'closed']);
                    })
                    ->visible(fn ($record) =>
                        $record->status === 'pending' &&
                        $record->makejob->user_id === Auth::id() &&
                        !$record->makejob->applications()->where('status', 'accepted')->exists()
                    )
                    ->requiresConfirmation(),
                Action::make('reject')
                    ->action(function ($record) {
                        $record->update(['status' => 'rejected']);
                    })
                    ->visible(fn ($record) =>
                        $record->status === 'pending' &&
                        $record->makejob->user_id === Auth::id() &&
                        !$record->makejob->applications()->where('status', 'accepted')->exists()
                    )
                    ->requiresConfirmation(),
            ]);
    }
}
