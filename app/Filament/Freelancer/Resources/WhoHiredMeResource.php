<?php

namespace App\Filament\Freelancer\Resources;

use App\Models\Hire;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Freelancer\Resources\WhoHiredMeResource\Pages;

class WhoHiredMeResource extends Resource
{
    protected static ?string $model = Hire::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Hiring Management';
    protected static ?string $navigationParentItem = 'Hires Management';

    protected static ?string $navigationLabel = 'Who Hired Me';

    protected static ?string $modelLabel = 'Hire';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('price')
                    ->numeric()
                    ->prefix('MYR')
                    ->required(),
                Toggle::make('is_online')
                    ->label('Online Job')
                    ->default(false),
                Select::make('state_id')
                    ->relationship('state', 'name')
                    ->searchable()
                    ->preload()
                    ->visible(fn (callable $get) => !$get('is_online')),
                Select::make('district_id')
                    ->relationship('district', 'name')
                    ->searchable()
                    ->preload()
                    ->visible(fn (callable $get) => !$get('is_online')),
                TextInput::make('description')
                    ->columnSpan(2)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('client.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                    ->money('MYR')
                    ->sortable(),
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
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Action::make('accept')
                    ->label('Accept')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(function (Hire $record) {
                        $record->update(['status' => 'accepted']);
                    })
                    ->visible(fn (Hire $record) => $record->status === 'pending' && $record->client_id !== Auth::id()),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->action(function (Hire $record) {
                        $record->update(['status' => 'rejected']);
                    })
                    ->visible(fn (Hire $record) => $record->status === 'pending' && $record->client_id !== Auth::id()),
            ])
            ->bulkActions([])
            ->modifyQueryUsing(fn (Builder $query) => $query->where('freelancer_id', Auth::id()));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWhoHiredMe::route('/'),
            'view' => Pages\ViewWhoHiredMe::route('/{record}'),
        ];
    }
}
