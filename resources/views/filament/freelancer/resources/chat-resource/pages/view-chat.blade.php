<x-filament-panels::page>
    <style>
        .chat-gradient-bg {
            background: linear-gradient(145deg, #ecfffe 0%, #5979e1 100%);
        }
        .chat-bubble-appear {
            animation: slideIn 0.3s ease-out;
        }
        .typing-indicator span {
            animation: bounce 0.8s infinite;
        }
        .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
        .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
        }
    </style>
    @php
        $otherUser = $this->record->user_one_id === auth()->id()
            ? $this->record->userTwo
            : $this->record->userOne;
    @endphp
    <div class="flex flex-col h-[calc(100vh-150px)] w-full rounded-3xl shadow-xl border border-indigo-100/50 chat-gradient-bg">
        <!-- Chat Header -->
        <div class="flex items-center justify-between p-2 sm:p-6 border-b border-indigo-100 bg-white/90 dark:bg-gray-800/90 rounded-t-3xl backdrop-blur-sm">
            <!-- Other User -->
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full border-3 border-gray-200 shadow-md bg-gradient-to-br from-gray-600 to-gray-800 flex items-center justify-center text-white text-lg sm:text-xl font-bold overflow-hidden" aria-label="User avatar">
                    @if(!empty($otherUser->avatar))
                        <img src="{{ asset('storage/' . $otherUser->avatar) }}" alt="{{ $otherUser->name }}'s avatar" class="w-full h-full object-cover rounded-full" />
                    @else
                        {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                    @endif
                </div>
                <div>
                    <h3 style="margin-left: 20px; class="text-lg sm:text-xl font-bold text-indigo-700 dark:text-indigo-300 tracking-tight">{{ $otherUser->name }}</h3>
                    <p style="margin-left: 20px; class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">{{ $otherUser->email }}</p>
                </div>
            </div>

        </div>

        <!-- Messages Container -->
        <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-4 bg-transparent" id="messages-container" wire:poll.2s>
            @forelse($this->chatMessages() as $message)
                <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }} chat-bubble-appear">
                    <div class="flex {{ $message->sender_id === auth()->id() ? 'flex-row-reverse' : 'flex-row' }} items-end space-x-2 sm:space-x-3 max-w-[80%] sm:max-w-md">
                        @if($message->sender_id !== auth()->id())
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full border-3 border-gray-200 shadow-md bg-gradient-to-br from-gray-600 to-gray-800 flex items-center justify-center text-white text-lg sm:text-xl font-bold transition-transform hover:scale-105 overflow-hidden" aria-label="User avatar">
                                @if(!empty($otherUser->avatar))
                                    <img src="{{ asset('storage/' . $otherUser->avatar) }}" alt="{{ $otherUser->name }}'s avatar" class="w-full h-full object-cover rounded-full" />
                                @else
                                    {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                                @endif
                            </div>
                        @endif
                        <div style="margin-left: 10px; class="flex flex-col {{ $message->sender_id === auth()->id() ? 'items-end' : 'items-start' }}">
                            <div class="px-4 py-2.5 rounded-2xl shadow-lg
                                {{ $message->sender_id === auth()->id()
                                    ? 'bg-gradient-to-br from-indigo-600 to-indigo-900 text-white rounded-br-sm'
                                    : 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-bl-sm' }}
                                transition-all hover:shadow-xl border border-indigo-100 dark:border-gray-700">
                                <p class="text-sm sm:text-base leading-relaxed font-medium">{{ $message->message }}</p>
                            </div>
                            <div class="flex items-center space-x-1 mt-1.5">
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                                    {{ $message->created_at->format('M j, g:i A') }}
                                </span>
                                @if($message->sender_id === auth()->id())
                                    <svg class="w-3 h-3 text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center h-full text-gray-500 dark:text-gray-400">
                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                    <p class="text-sm font-medium">No messages yet. Start the conversation!</p>
                </div>
            @endforelse
            <div x-data="{ typing: false }" x-show="typing" class="flex items-center space-x-1 p-3">
                <div class="typing-indicator flex space-x-1">
                    <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                    <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                    <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                </div>
                <span class="text-xs text-gray-500">Typing...</span>
            </div>
        </div>

        <!-- Message Input (Filament Form) -->
        <div class="p-4 sm:p-6 bg-white/90 dark:bg-gray-800/90 rounded-b-3xl backdrop-blur-sm border-t border-indigo-100/50">
            <form wire:submit.prevent="sendMessage" class="flex items-end gap-2 sm:gap-3">
                <div class="flex-1">
                    <textarea
                        wire:model.defer="message"
                        class="w-full rounded-xl border border-indigo-200 dark:border-gray-700 p-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none resize-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                        rows="2"
                        maxlength="1000"
                        placeholder="Type your message..."
                        required
                    ></textarea>
                </div>
                <div class="flex items-center space-x-2">
                    <button
                        type="submit"
                        class="px-6 py-3 bg-indigo-600 text-white rounded-xl shadow-lg hover:bg-indigo-700 transition text-lg font-bold flex items-center gap-2"
                    >
                        <span class="hidden sm:inline">Send</span>
                        <svg class="w-6 h-6 sm:ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:updated', function () {
            const container = document.getElementById('messages-container');
            container.scrollTo({
                top: container.scrollHeight,
                behavior: 'smooth'
            });
        });

        window.addEventListener('load', function () {
            const container = document.getElementById('messages-container');
            container.scrollTo({
                top: container.scrollHeight,
                behavior: 'smooth'
            });
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('chat', () => ({
                typing: false,
                init() {
                    this.$watch('typing', value => {
                        if (value) {
                            setTimeout(() => this.typing = false, 2000);
                        }
                    });
                }
            }));
        });
    </script>
</x-filament-panels::page>