<?php

namespace App\Filament\Freelancer\Resources;

use App\Models\Artwork;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions;
use Filament\Tables\Columns\Concerns\CanBeToggled;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TagsColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Stack;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Auth;
use App\Filament\Freelancer\Resources\ArtworkResource\Pages;
use Kingmaker\FilamentFlexLayout\Enums\HorizontalArrangement;
use Kingmaker\FilamentFlexLayout\Infolists\Flex as InfolistFlex;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Grid as FormGrid;
use Filament\Infolists\Components\Component;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Model;
use Filament\Infolists\Components\Grid as InfolistGrid;
use Hugomyb\FilamentMediaAction\Infolists\Components\Actions\MediaAction;
use Filament\Infolists\Components\IconButton;
use Filament\Infolists\Components\Actions\Action;
use Filament\Support\Facades\Filament;
//use Hugomyb\FilamentMediaAction\Infolists\Components\Actions\MediaAction;


class ArtworkResource extends Resource
{
    protected static ?string $model = Artwork::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Artworks';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                FileUpload::make('image')
                    ->label('Artwork Image')
                    ->image()
                    ->multiple()
                    ->imageEditor()
                    ->directory('artworks')
                    ->visibility('public')
                    ->required()
                    ->columnSpanFull()
                    ->panelLayout('grid')
                    ->grow(false),
                TextInput::make('media')
                    ->label('Media URL')
                    ->url()
                    ->placeholder('Enter media URL (YouTube, Vimeo, etc.)')
                    ->helperText('Enter a URL for video, audio, or PDF content')
                    ->columnSpanFull(),
                RichEditor::make('description')
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'link',
                        'orderedList',
                        'unorderedList',
                    ])
                    ->columnSpanFull(),
                FormGrid::make(2)
                    ->schema([
                        TagsInput::make('tags')
                            ->separator(',')
                            ->columnSpanFull(),
                        Toggle::make('publish')
                            ->label('Publish')
                            ->onColor('success')
                            ->offColor('danger'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'sm' => 1,
                'md' => 2,
                'lg' => 2,
                'xl' => 3,
            ])
            ->columns([
                Grid::make()
                    ->schema([
                        ImageColumn::make('image')
                            ->label('')
                            ->limit(1)
                            ->size(150)
                            ->extraImgAttributes(['class' => 'object-cover w-full h-full rounded-lg']),
                        Stack::make([
                            TextColumn::make('title')
                                ->label('Title')
                                ->searchable()
                                ->weight(FontWeight::Bold)
                                ->size(TextColumnSize::Large)
                                ->limit(40)
                                ->tooltip(function (TextColumn $column): ?string {
                                    $state = $column->getState();
                                    if (strlen($state) <= 20) {
                                        return null;
                                    }
                                    return $state;
                                }),
                            TextColumn::make('likes_count')
                                ->label('Likes')
                                ->sortable()
                                ->icon('heroicon-o-heart')
                                ->iconColor('warning'),
                            TagsColumn::make('tags')
                                ->label('Tags')
                                ->searchable()
                                ->limit(3)
                                ->separator(','),
                        ])->space(1),
                    ])
                    ->columns([
                        'sm' => 1,
                        'md' => 2,
                    ])
                    ->extraAttributes(['class' => 'p-3 gap-3 rounded-lg border border-gray-200 dark:border-gray-700'])
                    ->columnSpanFull(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([12, 24, 36, 48])
            ->actions([
                ViewAction::make(),
                \Filament\Tables\Actions\Action::make('like')
                    ->icon(fn (Artwork $record) => $record->isLikedBy(\Filament\Facades\Filament::auth()->user()) ? 'heroicon-s-heart' : 'heroicon-o-heart')
                    ->color(fn (Artwork $record) => $record->isLikedBy(\Filament\Facades\Filament::auth()->user()) ? 'warning' : 'gray')
                    ->action(function (Artwork $record) {
                        $isLiked = $record->toggleLike(\Filament\Facades\Filament::auth()->user());
                        $record->refresh();
                    })
                    ->label(fn (Artwork $record) => $record->likes_count)
                    ->tooltip('Like this artwork'),
            ])
            ->filters([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Artwork Images')
                    ->icon('heroicon-o-photo')
                    ->collapsible()
                    ->headerActions([
                        MediaAction::make('media')
                            ->label('URL Media')
                            ->color('danger')
                            ->icon('heroicon-o-link')
                            ->media(fn (Artwork $record): ?string => $record->media)
                            ->visible(fn (Artwork $record): bool => filled($record->media)),
                    ])
                    ->schema([
                        InfolistFlex::make([
                            ImageEntry::make('image')
                                ->label('')
                                ->size(200)
                                ->extraImgAttributes(['class' => 'object-contain rounded-lg'])
                                ->grow(false),
                        ])
                        ->horizontalArrangement(HorizontalArrangement::Evenly)
                        ->gap(4)
                        ->columnSpanFull(),
                    ]),
                Section::make('Artwork Details')
                    ->icon('heroicon-o-information-circle')
                    ->collapsible()
                    ->schema([
                        TextEntry::make('title')
                            ->label('Title')
                            ->size(\Filament\Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight(FontWeight::Bold)
                            ->columnSpanFull(),
                        TextEntry::make('description')
                            ->label('Description')
                            ->markdown()
                            ->columnSpanFull(),
                        TextEntry::make('tags')
                            ->label('Tags')
                            ->badge()
                            ->separator(',')
                            ->columnSpanFull(),
                        TextEntry::make('likes_count')
                            ->label('Likes')
                            ->badge()
                            ->color('success'),
                        Actions::make([
                            Action::make('like')
                                ->label('Like')
                                ->icon(fn (Artwork $record) => $record->isLikedBy(\Filament\Facades\Filament::auth()->user()) ? 'heroicon-m-heart' : 'heroicon-o-heart')
                                ->color(fn (Artwork $record) => $record->isLikedBy(\Filament\Facades\Filament::auth()->user()) ? 'warning' : 'gray')
                                ->action(function (Artwork $record) {
                                    $isLiked = $record->toggleLike(\Filament\Facades\Filament::auth()->user());
                                    $record->refresh();
                                })
                                ->tooltip('Like this artwork'),
                        ]),
                        Section::make('Published By')
                            ->icon('heroicon-o-user')
                            ->schema([
                                InfolistGrid::make(2)
                                    ->schema([
                                        InfolistFlex::make([
                                            ImageEntry::make('user.avatar')
                                                ->label('')
                                                ->circular()
                                                ->size(40)
                                                ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->user->name) . '&color=7F9CF5&background=EBF4FF')
                                                ->grow(false),
                                            TextEntry::make('user.name')
                                                ->label('Name'),
                                        ])
                                        ->horizontalArrangement(HorizontalArrangement::Start)
                                        ->gap(4),
                                        TextEntry::make('created_at')
                                            ->label('Created At')
                                            ->dateTime(),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArtworks::route('/'),
            'view' => Pages\ViewArtwork::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('publish', true);

    }

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }
}
