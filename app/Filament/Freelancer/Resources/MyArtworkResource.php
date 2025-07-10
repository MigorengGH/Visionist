<?php

namespace App\Filament\Freelancer\Resources;

use App\Actions\ResetStars;
use App\Models\Artwork;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Forms\Components\Grid as FormGrid;
use Hugomyb\FilamentMediaAction\Tables\Actions\MediaAction;
use Filament\Infolists\Components\Section as InfolistSection;
use App\Filament\Freelancer\Resources\MyArtworkResource\Pages;
use Filament\Tables\Actions\ActionGroup;

class MyArtworkResource extends Resource
{
    protected static ?string $model = Artwork::class;

    protected static ?string $navigationParentItem = 'Artworks';

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'My Artworks';

    protected static ?string $modelLabel = 'Artwork';

    protected static ?string $slug = 'my-artworks';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Basic Information')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->placeholder('Enter artwork title'),
                        RichEditor::make('description')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'link',
                                'orderedList',
                                'unorderedList',
                            ])
                            ->columnSpanFull()
                            ->placeholder('Describe your artwork...'),
                    ]),
                Section::make('Media')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Artwork Images')
                            ->image()
                            ->multiple()
                            ->imageEditor()
                            ->directory('artworks')
                            ->visibility('public')
                            ->required()
                            ->columnSpanFull()
                            ->panelLayout('grid')
                            ->grow(false)
                            ->maxFiles(5)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('Upload up to 5 images. Supported formats: JPG, PNG, WEBP'),
                        TextInput::make('media')
                            ->label('Media URL')
                            ->url()
                            ->placeholder('Enter media URL (YouTube, Vimeo, etc.)')
                            ->helperText('Enter a URL for video, audio, or PDF content')
                            ->columnSpanFull(),
                    ]),
                Section::make('Settings')
                    ->icon('heroicon-o-cog')
                    ->schema([
                        FormGrid::make(2)
                            ->schema([
                                TagsInput::make('tags')
                                    ->separator(',')
                                    ->placeholder('Add tags...')
                                    ->helperText('Separate tags with commas')
                                    ->columnSpanFull(),
                                Toggle::make('publish')
                                    ->label('Publish')
                                    ->helperText('Make this artwork visible to others')
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->inline(false),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                    ->icon('heroicon-o-heart')
                    ->iconColor('warning')
                    ->sortable(),
                TagsColumn::make('tags')
                    ->limit(3)
                    ->searchable()
                    ->toggleable(true),

                ToggleColumn::make('publish')
                    ->label('Publish')
                    ->onIcon('heroicon-o-check')
                    ->offIcon('heroicon-o-x-mark')
                    ->onColor('success')
                    ->offColor('danger'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(true),

            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                ActionGroup::make([
                EditAction::make(),
                DeleteAction::make(),
                MediaAction::make('media')
                    ->icon('heroicon-o-play')
                    ->media(fn (Artwork $record): ?string => $record->media)
                    ->visible(fn (Artwork $record): bool => filled($record->media)),
                    ]),

            ])
            ->headerActions([
                \Filament\Tables\Actions\CreateAction::make(),
            ]);
    }

    // public static function infolist(Infolist $infolist): Infolist
    // {
    //     return $infolist
    //         ->schema([
    //             InfolistSection::make('Artwork Images')
    //                 ->icon('heroicon-o-photo')
    //                 ->collapsible()
    //                 ->schema([
    //                     ImageEntry::make('image')
    //                         ->label('')
    //                         ->size(200)
    //                         ->extraImgAttributes(['class' => 'object-contain rounded-lg']),
    //                 ]),
    //             InfolistSection::make('Media')
    //                 ->icon('heroicon-o-play')
    //                 ->collapsible()
    //                 ->schema([
    //                 ]),
    //             InfolistSection::make('Artwork Details')
    //                 ->icon('heroicon-o-information-circle')
    //                 ->collapsible()
    //                 ->schema([
    //                     TextEntry::make('title')
    //                         ->label('Title')
    //                         ->size(\Filament\Infolists\Components\TextEntry\TextEntrySize::Large)
    //                         ->weight(\Filament\Support\Enums\FontWeight::Bold),
    //                     TextEntry::make('description')
    //                         ->label('Description')
    //                         ->markdown(),
    //                     TextEntry::make('tags')
    //                         ->label('Tags')
    //                         ->badge()
    //                         ->separator(','),
    //                     TextEntry::make('publish')
    //                         ->label('Status')
    //                         ->badge()
    //                         ->color(fn (bool $state): string => $state ? 'success' : 'danger')
    //                         ->formatStateUsing(fn (bool $state): string => $state ? 'Published' : 'Draft'),
    //                     TextEntry::make('created_at')
    //                         ->label('Created At')
    //                         ->dateTime(),

    //                 ]),
    //         ]);
    // }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMyArtworks::route('/'),
            'create' => Pages\CreateMyArtwork::route('/create'),
            'edit' => Pages\EditMyArtwork::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::id());
    }

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return true;
    }

    public static function canEdit(Model $record): bool
    {
        return $record->user_id === Auth::id();
    }

    public static function canDelete(Model $record): bool
    {
        return $record->user_id === Auth::id();
    }
}
