<?php

namespace App\Filament\Freelancer\Resources;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use App\Filament\Freelancer\Resources\ChatResource\Pages;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatResource extends Resource
{
    protected static ?string $model = Chat::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Chats';
    protected static ?string $slug = 'chats';

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('other_user')
                    ->label('Chat with')
                    ->getStateUsing(function ($record) {
                        $currentUserId = Auth::id();
                        return $record->user_one_id === $currentUserId
                            ? $record->userTwo->name
                            : $record->userOne->name;
                    })
                    ->searchable(),
                TextColumn::make('messages_count')
                    ->counts('messages')
                    ->label('Messages'),
                TextColumn::make('last_message')
                    ->label('Last Message')
                    ->getStateUsing(function ($record) {
                        $lastMessage = $record->messages()->latest()->first();
                        return $lastMessage ? Str::limit($lastMessage->message, 50) : 'No messages yet';
                    }),
            ])
            ->actions([
                Action::make('view')
                    ->label('Open Chat')
                    ->color('success')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->url(fn ($record) => static::getUrl('view', ['record' => $record]))
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        $userId = Auth::id();
        return parent::getEloquentQuery()
            ->where(function ($query) use ($userId) {
                $query->where('user_one_id', $userId)
                      ->orWhere('user_two_id', $userId);
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChats::route('/'),
            'view' => Pages\ViewChat::route('/{record}'),
        ];
    }
}
