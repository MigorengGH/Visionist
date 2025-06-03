<?php

namespace App\Filament\Freelancer\Resources\MyjobResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Models\Makejob;

class ApplicationsRelationManager extends RelationManager
{
    protected static string $relationship = 'applications';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('proposed_price')
                    ->numeric()
                    ->prefix('MYR')
                    ->required(),
                RichEditor::make('cover_letter')
                    ->required(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Applicant')
                    ->searchable(),
                TextColumn::make('proposed_price')
                    ->money('MYR')
                    ->sortable(),
                TextColumn::make('cover_letter')
                    ->limit(50)
                    ->searchable(),
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'accepted',
                        'danger' => 'rejected',
                    ]),
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
                Action::make('accept')
                    ->label('Accept')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(function ($record) {
                        $record->update(['status' => 'accepted']);

                        // Force update in DB to avoid model events overwriting
                        DB::table('makejobs')
                            ->where('id', $this->ownerRecord->id)
                            ->update([
                                'status' => 'accepted',
                                'accepted_user_id' => $record->user_id,
                                'negotiated_price' => $record->proposed_price,
                            ]);

                        $this->ownerRecord->applications()
                            ->where('id', '!=', $record->id)
                            ->update(['status' => 'rejected']);
                    })
                    ->visible(fn ($record) => $record && $record->status === 'pending'),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->action(function ($record) {
                        $record->update(['status' => 'rejected']);
                    })
                    ->visible(fn ($record) => $record->status === 'pending'),
                //view cv
                Action::make('view_cv')
                    ->label('View Application')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Application Details')
                    ->modalSubheading(fn ($record) => 'Submitted by ' . $record->user->name)
                    ->modalContent(fn ($record) => view('filament.freelancer.resources.myjob-resource.partials.application-modal', [
                        'record' => $record
                    ]))
                    ->modalActions([
                        Action::make('accept')
                            ->label('Accept')
                            ->color('success')
                            ->icon('heroicon-o-check')
                            ->action(function ($record) {
                                $record->update(['status' => 'accepted']);
                                DB::table('makejobs')
                                    ->where('id', $this->ownerRecord->id)
                                    ->update([
                                        'status' => 'accepted',
                                        'accepted_user_id' => $record->user_id,
                                        'negotiated_price' => $record->proposed_price,
                                    ]);
                                $this->ownerRecord->applications()
                                    ->where('id', '!=', $record->id)
                                    ->update(['status' => 'rejected']);
                            })
                            ->visible(fn ($record) => $record->status === 'pending'),
                    ])
                    ->modalWidth('lg'),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
