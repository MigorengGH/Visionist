<?php

namespace App\Filament\Freelancer\Resources;

use App\Models\Makejob;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\RichEditor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Freelancer\Resources\MyjobResource\Pages;
use App\Filament\Freelancer\Resources\MyjobResource\RelationManagers\ApplicationsRelationManager;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use App\Models\State;
use App\Models\District;
use Filament\Forms\Components\TagsInput;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\Grid as FormGrid;
use Filament\Infolists\Components\Grid as InfoGrid;

class MyjobResource extends Resource
{
    protected static ?string $model = Makejob::class;

    protected static ?string $breadcrumb = 'My Jobs';
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'My Jobs';

    protected static ?string $navigationGroup = 'Job';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FormGrid::make(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->disabled(fn (?Makejob $record) =>
                                $record && $record->applications()->exists()
                            ),
                        TextInput::make('budget')
                            ->numeric()
                            ->required()
                            ->prefix('MYR')
                            ->maxValue(999999.99)
                            ->disabled(fn (?Makejob $record) =>
                                $record && $record->applications()->exists()
                            ),
                        FormGrid::make(1)
                            ->schema([
                                Toggle::make('is_online')
                                    ->label('Online Job')
                                    ->helperText('Toggle if this job can be done remotely')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $set('state_id', null);
                                            $set('district_id', null);
                                            $set('location_type', 'online');
                                        } else {
                                            $set('location_type', 'physical');
                                        }
                                    })
                                    ->inline(false)
                                    ->disabled(fn (?Makejob $record) =>
                                        $record && $record->applications()->exists()
                                    ),
                                Hidden::make('location_type')
                                    ->default('physical'),
                            ]),
                        Select::make('state_id')
                            ->label('State')
                            ->options(State::pluck('name', 'id')->toArray())
                            ->required(fn (callable $get) => !$get('is_online'))
                            ->reactive()
                            ->hidden(fn (callable $get) => $get('is_online'))
                            ->disabled(fn (?Makejob $record) =>
                                $record && $record->applications()->exists()
                            )
                            ->afterStateUpdated(fn ($set) => $set('district_id', null)),
                        Select::make('district_id')
                            ->label('District')
                            ->disabled(fn (?Makejob $record) =>
                                $record && $record->applications()->exists()
                            )
                            ->hidden(fn (callable $get) => $get('is_online'))
                            ->required(fn (callable $get) => !$get('is_online'))
                            ->options(function (callable $get, $record) {
                                $stateId = $get('state_id');
                                $query = District::query();
                                if ($stateId) {
                                    $query->where('state_id', $stateId);
                                }
                                if ($record && $record->district_id) {
                                    $query->orWhere('id', $record->district_id);
                                }
                                return $query->pluck('name', 'id')->toArray();
                            })
                            ->reactive(),
                        TagsInput::make('tags')
                            ->label('Tags')
                            ->placeholder('Add tags...')
                            ->helperText('Separate tags with commas')
                            ->columnSpanFull()
                            ->disabled(fn (?Makejob $record) =>
                                $record && $record->applications()->exists()
                            ),
                    ]),
                RichEditor::make('description')
                ->disableToolbarButtons([
                    'attachFiles',
                    'blockquote',
                    'bold',
                    'bulletList',
                    'codeBlock',
                    'h2',
                    'h3',
                    'italic',
                    'link',
                    'orderedList',
                    'redo',
                    'strike',
                    'underline',
                    'undo',
                ])
                    ->required()

                    ->columnSpanFull()
                    ->disabled(fn (?Makejob $record) =>
                        $record && $record->applications()->exists()
                    ),
                Select::make('status')
                    ->options([
                        'open' => 'Open',
                        'closed' => 'closed',
                    ])
                    ->default('open')
                    ->visible(fn (?Makejob $record) =>
                        $record &&
                        (
                            ($record->status === 'open' && !$record->applications()->exists())
                            || $record->status === 'closed'
                        )
                    )
                    ->disabled(fn (?Makejob $record) =>
                        $record && $record->applications()->exists()
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('is_online')
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => $state ? 'Online' : 'Physical')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'primary'),
                TextColumn::make('state_id')
                    ->label('State')
                    ->icon('heroicon-o-map')
                    ->color('info')
                    ->getStateUsing(fn ($record) => $record->is_online ? 'Online' : (optional(\App\Models\State::find($record->state_id))->name ?? '')),
                TextColumn::make('district_id')
                    ->label('District')
                    ->icon('heroicon-o-map-pin')
                    ->color('primary')
                    ->getStateUsing(fn ($record) => $record->is_online ? 'Online' : (optional(\App\Models\District::find($record->district_id))->name ?? '')),
                TextColumn::make('tags')
                    ->label('Tags')
                    ->badge()
                    ->limit('3')
                    ->toggleable(true)
                    ->toggledHiddenByDefault()
                    ->sortable(),
                TextColumn::make('budget')
                    ->money('MYR')
                    ->sortable(),
                BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'open',
                        'danger' => 'close',
                        'success' => 'accepted',
                    ]),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('view_contract')
                    ->label('View Contract')
                    ->icon('heroicon-o-document-text')
                    ->color('success')
                    ->url(fn (Makejob $record) => route('filament.freelancer.resources.myjobs.view-contract', $record))
                    ->visible(fn (Makejob $record) => $record->status === 'accepted'),
                Action::make('downloadContract')
                    ->label('Download Contract')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function (Makejob $record) {
                        $acceptedApplication = $record->applications()
                            ->where('status', 'accepted')
                            ->first();

                        if (!$acceptedApplication) {
                            Notification::make()
                                ->title('No accepted application found')
                                ->danger()
                                ->send();
                            return;
                        }

                        $pdf = Pdf::loadView('contracts.job-contract', [
                            'job' => $record,
                            'client' => $record->user,
                            'freelancer' => $acceptedApplication->user,
                            'application' => $acceptedApplication,
                        ]);

                        // Save the PDF temporarily
                        $pdfPath = "contracts/contract-{$record->id}.pdf";
                        Storage::put($pdfPath, $pdf->output());

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, "contract-{$record->id}.pdf");
                    })
                    ->visible(fn (Makejob $record) => $record->status === 'accepted'),
                EditAction::make()
                    ->visible(fn (Makejob $record) =>
                        !$record->applications()->exists()
                    ),
                DeleteAction::make()
                    ->visible(fn (Makejob $record) =>
                        !$record->applications()->exists()
                    ),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMyjobs::route('/'),
            'create' => Pages\CreateMyjob::route('/create'),
            'edit' => Pages\EditMyjob::route('/{record}/edit'),
            'applications' => Pages\Applications::route('/{record}/applications'),
            'view-contract' => Pages\ViewContract::route('/{record}/contract'),
            'download-pdf' => Pages\DownloadPdf::route('/download-pdf'),
        ];
    }

    public static function getRoutes(): \Illuminate\Routing\RouteCollection
    {
        return parent::getRoutes()
            ->addRoute(
                \Illuminate\Routing\Route::get(
                    '/download-pdf',
                    [Pages\DownloadPdf::class, 'download']
                )->name('download-pdf')
            );
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::id());
    }

    public static function create(Form $form): array
    {
        $data = $form->getState();
        $data['user_id'] = Auth::id();
        return $data;
    }

    public static function getRelations(): array
    {
        return [
            ApplicationsRelationManager::class,
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Job Details')
                    ->schema([
                        InfoGrid::make(2)
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
                                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state),
                            ]),
                    ]),
            ]);
    }
}
