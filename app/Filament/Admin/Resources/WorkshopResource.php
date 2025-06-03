<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Faker\Core\File;
use Filament\Tables;
use App\Models\State;
use App\Models\District;
use App\Models\Workshop;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Container\Attributes\Tag;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\WorkshopResource\Pages;
use Filament\Infolists\Components\Split as ComponentsSplit;
use App\Filament\Admin\Resources\WorkshopResource\RelationManagers;

class WorkshopResource extends Resource
{
    protected static ?string $model = Workshop::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Workshop Information')
                ->description('Please fill in the following workshop information.')
                ->collapsible()
                    ->schema([
                        TextInput::make('name')
                            ->label('Title')
                            ->required(),
                        DateTimePicker::make('start_date')
                            ->label('Date & Time')
                            ->prefixIcon('heroicon-o-calendar')
                            ->native(false)
                            ->seconds(false)
                            ->minDate(now()->setTimezone('Asia/Kuala_Lumpur'))
                            ->displayFormat('M d, Y -- H:i') // Format as "Feb 12, 2024 10:10"
                            ->required(),
                        Select::make('state_id')
                            ->label('State')
                            ->options(State::pluck('name', 'id'))
                            ->native(false)
                            ->preload()
                            ->searchable()
                            ->prefixIcon('heroicon-o-map')
                            ->live()
                            ->afterStateUpdated(fn (callable $set) => $set('district_id', null))
                            ->required(),
                        Select::make('district_id')
                            ->label('District')
                            ->native(false)
                            ->preload()
                            ->searchable()
                            ->options(function (callable $get) {
                                $stateId = $get('state_id');

                                if (!$stateId) {
                                    return [];
                                }

                                return \App\Models\District::where('state_id', $stateId)
                                    ->pluck('name', 'id');
                            })
                            ->prefixIcon('heroicon-o-map-pin')
                            ->required(),
                        TextInput::make('price')
                            ->label('Price')
                            ->prefix('MYR')
                            ->numeric() // Ensures only numbers
                            ->rules(['regex:/^\d+(\.\d{1,2})?$/'])
                            ->columnSpanFull()
                            ->required(),
                        RichEditor::make('description')
                            ->label('Description')
                            ->disableAllToolbarButtons()
                            ->columnSpanFull()
                            ->required(),

                    ])->columnSpan(2)->columns(2),
                Section::make('Meta')
                ->description('Please fill in the information.')
                ->collapsible()
                    ->schema([

                    FileUpload::make('image')
                        ->label('Image')
                        ->multiple()
                        ->disk('public') // Use the correct disk
                        ->directory('WorkshopImage') // Folder inside storage/app/public
                        ->visibility('public') // Make it publicly accessible
                        ->image() // Only allow image files
                        ->preserveFilenames() // Keep original file names
                        ->deleteUploadedFileUsing(function ($file) {
                            if ($file) {
                                Storage::disk('public')->delete($file);
                            }
                        })
                        ->required(),

                    TagsInput::make('tags')
                        ->label('Tags')
                        ->prefixIcon('heroicon-o-tag')
                        ->required(),

                    Toggle::make('publish')
                        ->label('Publish')
                        ->onIcon('heroicon-o-check')
                        ->offIcon('heroicon-o-x-mark')
                        ->onColor('success')
                        ->offColor('danger')
                        ->default(false),

                ])->columnSpan(1)




            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->stacked()
                    ->wrap()
                    ->limitedRemainingText()
                    ->limit(3),
                TextColumn::make('name')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('start_date')
                    ->label('Date')
                    ->dateTime('M d, Y')
                    ->searchable()
                    ->sortable(),
                ToggleColumn::make('publish')
                    ->label('Publish')
                    ->onIcon('heroicon-o-check')
                    ->offIcon('heroicon-o-x-mark')
                    ->onColor('success')
                    ->offColor('danger')
                    ->sortable(),
                TextColumn::make('district.state.name')
                    ->label('State')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('district.name')
                    ->label('District')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                    ->label('Price')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => $state == 0.00 ? 'FREE' : 'RM ' . number_format($state, 2))
                    ->badge()
                    ->color(fn ($state) => $state == 0.00 ? 'success' : 'warning'), // Gray for FREE, Green for price
                TextColumn::make('tags')
                    ->searchable()
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault:true),
            ])
            ->filters([
                SelectFilter::make('state_id')
                    ->label('State')
                    ->options(State::pluck('name', 'id'))
                    ->searchable()
                    ->preload(),
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListWorkshops::route('/'),
            'create' => Pages\CreateWorkshop::route('/create'),
            //'edit' => Pages\EditWorkshop::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


}
