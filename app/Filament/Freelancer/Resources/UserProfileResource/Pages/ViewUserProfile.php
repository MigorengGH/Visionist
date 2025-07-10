<?php

namespace App\Filament\Freelancer\Resources\UserProfileResource\Pages;

use Filament\Forms;
use App\Models\Chat;
use App\Models\Hire;
use App\Models\State;
use Filament\Actions;
use App\Models\District;
use Filament\Forms\Components\Tabs;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\RichEditor;
use App\Filament\Freelancer\Resources\UserProfileResource;
use App\Filament\Freelancer\Resources\UserProfileResource\Widgets\UserStatsWidget;

class ViewUserProfile extends ViewRecord
{
    protected static string $resource = UserProfileResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            UserStatsWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        if (Auth::id() === $this->record->id) {
            return [];
        }
        return [
            Actions\Action::make('chat')
            ->label('Message')
            ->icon('heroicon-o-chat-bubble-left-right')
            ->color('success')
            ->button()
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
            }),
            Actions\Action::make('hire')
                ->label('Hire')
                ->icon('heroicon-m-fire')
                ->form([
                    Tabs::make('Hiring')
                        ->tabs([
                            Tab::make('Job Details')
                                ->schema([
                                    TextInput::make('title')
                                        ->required()
                                        ->maxLength(255),
                                    Toggle::make('is_online')
                                        ->label('Online Job')
                                        ->default(false)
                                        ->live(),

                                    TextInput::make('price')
                                        ->numeric()
                                        ->prefix('MYR')
                                        ->required(),
                                    Select::make('state_id')
                                        ->label('State')
                                        ->options(State::pluck('name', 'id'))
                                        ->searchable()
                                        ->preload()
                                        ->visible(fn (callable $get) => !$get('is_online')),
                                    Select::make('district_id')
                                        ->label('District')
                                        ->options(function (callable $get) {
                                            $stateId = $get('state_id');
                                            if (!$stateId) {
                                                return [];
                                            }
                                            return District::where('state_id', $stateId)->pluck('name', 'id');
                                        })
                                        ->searchable()
                                        ->preload()
                                        ->visible(fn (callable $get) => !$get('is_online')),
                                    TextInput::make('description')
                                        ->required()
                                        ->maxLength(1000),
                                ]),
                            Tab::make('Hiring Status')
                                ->schema([
                                    Forms\Components\View::make('filament.freelancer.resources.user-profile-resource.pages.hiring-status')
                                        ->viewData([
                                            'hires' => Hire::where('client_id', Auth::id())
                                                ->where('freelancer_id', $this->record->id)
                                                ->latest()
                                                ->get()
                                        ]),
                                ]),
                        ]),
                ])
                ->action(function (array $data) {
                    if (Auth::id() === $this->record->id) {
                        \Filament\Notifications\Notification::make()
                            ->title('You cannot hire yourself.')
                            ->danger()
                            ->send();
                        return;
                    }
                    Hire::create([
                        'title' => $data['title'],
                        'description' => $data['description'],
                        'price' => $data['price'],
                        'state_id' => $data['state_id'] ?? null,
                        'district_id' => $data['district_id'] ?? null,
                        'is_online' => $data['is_online'],
                        'client_id' => Auth::id(),
                        'freelancer_id' => $this->record->id,
                        'status' => 'pending',
                    ]);
                }),
        ];
    }
}
