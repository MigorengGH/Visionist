<?php

namespace App\Filament\Freelancer\Resources\ChatResource\Pages;

use App\Filament\Freelancer\Resources\ChatResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;

class ViewChat extends ViewRecord
{
    protected static string $resource = ChatResource::class;
    protected static string $view = 'filament.freelancer.resources.chat-resource.pages.view-chat';

    public $message;

    // For real-time updates
    protected $listeners = ['refreshMessages' => '$refresh'];

    public function sendMessage()
    {
        $this->validate([
            'message' => 'required|string',
        ]);
        Message::create([
            'chat_id' => $this->record->id,
            'sender_id' => Auth::id(),
            'message' => $this->message,
        ]);
        $this->message = '';
        $this->dispatch('$refresh');
        Notification::make()
            ->title('Message sent!')
            ->success()
            ->send();
    }

    public function chatMessages()
    {
        return $this->record->messages()->with('sender')->orderBy('created_at', 'asc')->get();
    }


}
