<?php

namespace App\Filament\Freelancer\Resources;

use App\Models\User;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use App\Filament\Freelancer\Resources\UserProfileResource\Pages;
use App\Filament\Freelancer\Resources\UserProfileResource\RelationManagers;
use RelationManagers\HiresRelationManager;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;
use Filament\Support\Enums\FontFamily;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Enums\ActionsPosition;

class UserProfileResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Find Users';
    protected static ?string $modelLabel = 'User Profile';
    protected static ?string $slug = 'user-profiles';

    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'sm' => 1,
                'md' => 2,
                'lg' => 4,   // 4 columns for large screens
                'xl' => 4,   // 4 columns for extra large screens
            ])
            ->columns([
                \Filament\Tables\Columns\Layout\Stack::make([
                ImageColumn::make('avatar')
                        ->label('')
                    ->circular()
                        ->size(200)
                        ->alignCenter()
                        ->extraImgAttributes([
                            'class' => 'border-4 border-white shadow-xl',
                            'style' => 'transition: transform 0.3s ease; transform: scale(1);'
                        ])
                        ->extraAttributes([
                            'class' => 'hover:scale-105 transition-transform'
                        ])
                        ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&color=3B82F6&background=EFF6FF'),
                TextColumn::make('name')
                        ->label('name')
                    ->searchable()
                        ->sortable()
                        ->weight(\Filament\Support\Enums\FontWeight::Bold)
                        ->size(\Filament\Tables\Columns\TextColumn\TextColumnSize::Large)
                        ->alignCenter()
                        ->fontFamily(FontFamily::Sans)
                        ->color('primary')
                        ->extraAttributes(['class' => 'mt-2']),
                    TextColumn::make('aboutme')
                        ->label('')
                        ->searchable()
                        ->limit(80)
                        ->alignCenter()
                        ->formatStateUsing(fn($state) => $state ? strip_tags($state) : 'No bio available')
                        ->color('gray')
                        ->size(\Filament\Tables\Columns\TextColumn\TextColumnSize::Small)
                        ->extraAttributes(['class' => 'mt-1 line-clamp-2']),
                    TagsColumn::make('tags')
                        ->label('')
                        ->searchable()
                        ->limit(6)
                        ->alignCenter()
                        ->formatStateUsing(fn($state) => $state ? $state : ['No tags'])
                        ->badge()
                        ->color('info')
                        ->extraAttributes(['class' => 'mt-2 flex justify-center flex-wrap gap-1']),
                ])
                ->extraAttributes([
                    'class' => 'bg-white dark:bg-gray-800 shadow-md rounded-2xl p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border border-gray-100 dark:border-gray-700',
                    'style' => 'min-height: 280px;'
                ])
                ->columnSpanFull(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([12]) // 12 items per page (4x3 grid)
            ->actionsPosition(ActionsPosition::AfterColumns)
            ->actions([
                Action::make('chat')
                    ->label('Message')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->button()
                    ->size('sm')
                    ->extraAttributes([
                        'class' => 'w-full max-w-[120px] mx-auto mt-3 hover:bg-success-600 transition-colors'
                    ])
                    ->action(function ($record) {
                        $currentUser = Auth::user();
                        $targetUser = $record;
                        $chat = Chat::between($currentUser->id, $targetUser->id)->first();
                        if (!$chat) {
                            $chat = Chat::create([
                                'user_one_id' => $currentUser->id,
                                'user_two_id' => $targetUser->id,
                            ]);
                        }
                        return redirect()->route('filament.freelancer.resources.chats.view', ['record' => $chat]);
                    })
                    ->visible(fn ($record) => $record->id !== Auth::id()),
            ])
            ->emptyStateHeading('No users found')
            ->emptyStateDescription('Try adjusting your search or filters')
            ->emptyStateIcon('heroicon-o-users');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        // Profile Header
                        Section::make()
                    ->schema([
                        ImageEntry::make('avatar')
                                    ->label('')
                            ->circular()
                                    ->size(200)
                                    ->alignCenter()
                                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&color=3B82F6&background=EFF6FF'),
                        TextEntry::make('name')
                                    ->label('')
                            ->size(\Filament\Infolists\Components\TextEntry\TextEntrySize::Large)
                                    ->weight(\Filament\Support\Enums\FontWeight::Bold)
                                    ->fontFamily(FontFamily::Sans)
                                    ->alignCenter()
                                    ->color('primary'),
                                TextEntry::make('aboutme')
                                    ->label('')
                                    ->markdown()
                                    ->alignCenter()
                                    ->color('gray')
                                    ->columnSpanFull()
                                    ->extraAttributes(['class' => 'mt-3 prose prose-sm max-w-none']),
                                \Filament\Infolists\Components\Group::make([
                        TextEntry::make('email')
                                        ->label(' ')
                                        ->icon('heroicon-o-envelope')
                                        ->iconColor('info')
                                        ->color('info')
                                        ->alignCenter(),
                        TextEntry::make('phone')
                                        ->label(' ')
                                        ->icon('heroicon-o-phone')
                                        ->iconColor('info')
                                        ->color('info')
                                        ->alignCenter(),
                                ])
                                ->columns(2)
                            ->columnSpanFull(),
                    ])
                            ->columns(1)
                            ->extraAttributes(['class' => 'bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 p-6 rounded-t-2xl']),

                        // Social Media
                        //hide section if not set instagram or youtube
                Section::make('Social Media')
                            ->hidden(fn ($record) => !$record->instagram && !$record->youtube)
                    ->schema([
                        TextEntry::make('instagram')
                            ->label('Instagram')
                            ->url(fn ($record) => $record->instagram)
                            ->openUrlInNewTab()
                                    ->icon('heroicon-o-camera')
                                    ->badge()
                                    ->color('purple')
                                    ->alignCenter()
                                    ->hidden(fn ($record) => !$record->instagram),
                        TextEntry::make('youtube')
                            ->label('YouTube')
                            ->url(fn ($record) => $record->youtube)
                            ->openUrlInNewTab()
                                    ->icon('heroicon-o-play')
                                    ->badge()
                                    ->color('danger')
                                    ->alignCenter()
                                    ->hidden(fn ($record) => !$record->youtube),
                    ])
                            ->columns(2)
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'bg-white dark:bg-gray-800 p-6']),

                        // Skills/Tags
                        Section::make('Skills & Expertise')
                            ->hidden(fn ($record) => empty($record->tags))
                            ->schema([
                                TextEntry::make('tags')
                                    ->label('')
                                    ->badge()
                                    ->separator(',')
                                    ->color('info')
                                    ->columnSpanFull()
                                    ->alignCenter()
                                    ->formatStateUsing(fn($state) => $state ? $state : ['No skills listed'])
                                    ->extraAttributes(['class' => 'flex flex-wrap justify-center gap-2']),
                            ])
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'bg-white dark:bg-gray-800 p-6']),

                        // Certificates show the title intead of id
                Section::make('Certificates')
                            ->hidden(fn ($record) => !$record->certificate_1 && !$record->certificate_2 && !$record->certificate_3)
                    ->schema([
                        TextEntry::make('certificate_1')
                            ->label('Certificate 1')

                                    ->badge()
                                    ->color('success')
                                    ->hidden(fn ($record) => !$record->certificate_1)
                                    ->formatStateUsing(function ($state) {
                                        if (!$state) return null;
                                        $cert = \App\Models\CertificateApplication::find($state);
                                        return $cert ? $cert->title : null;
                                    }),
                        TextEntry::make('certificate_2')
                            ->label('Certificate 2')

                                    ->badge()
                                    ->color('success')
                                    ->hidden(fn ($record) => !$record->certificate_2)
                                    ->formatStateUsing(function ($state) {
                                        if (!$state) return null;
                                        $cert = \App\Models\CertificateApplication::find($state);
                                        return $cert ? $cert->title : null;
                                    }),
                        TextEntry::make('certificate_3')
                            ->label('Certificate 3')

                                    ->badge()
                                    ->color('success')
                                    ->hidden(fn ($record) => !$record->certificate_3)
                                    ->formatStateUsing(function ($state) {
                                        if (!$state) return null;
                                        $cert = \App\Models\CertificateApplication::find($state);
                                        return $cert ? $cert->title : null;
                                    }),
                            ])
                            ->columns(3)
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'bg-white dark:bg-gray-800 p-6 rounded-b-2xl']),
                    ])
                    ->columns(1)
                    ->columnSpanFull()
                    ->extraAttributes(['class' => 'shadow-lg rounded-2xl overflow-hidden']),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ArtworksRelationManager::class,
            RelationManagers\JobsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserProfiles::route('/'),
            'view' => Pages\ViewUserProfile::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->where('isAdmin', false)
            ->whereNotNull('email_verified_at');
    }
}
