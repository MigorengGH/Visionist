<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-bold text-pink-600 dark:text-pink-400 flex items-center">
                    <x-heroicon-o-star class="w-6 h-6 mr-2 text-pink-500" /> Most Starred Artworks
                </h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($this->getTopArtworks() as $artwork)
                    <a href="{{ route('filament.freelancer.resources.artworks.view', ['record' => $artwork->id]) }}" class="block bg-gradient-to-br from-pink-100 via-rose-100 to-amber-100 dark:from-pink-900 dark:via-rose-900 dark:to-amber-900 rounded-xl shadow-lg p-4 flex flex-col items-center border-2 border-pink-200 dark:border-pink-700 hover:scale-105 hover:shadow-2xl transition-transform">
                        <img src="{{ $artwork->image_url ?? 'https://placehold.co/120x120' }}" alt="{{ $artwork->title }}" class="w-24 h-24 rounded-lg object-cover mb-2 border-4 border-white shadow" />
                        <div class="font-semibold text-lg text-gray-800 dark:text-gray-100 mb-1 text-center">{{ $artwork->title }}</div>
                        <div class="flex items-center gap-1 mt-1">
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-pink-200 text-pink-700 dark:bg-pink-500 dark:text-white text-xs font-bold">
                                <x-heroicon-o-heart class="w-4 h-4 mr-1 text-pink-500 dark:text-white" /> {{ $artwork->likes_count }} Likes
                            </span>
                        </div>
                    </a>
                @endforeach
                @if($this->getTopArtworks()->isEmpty())
                    <div class="col-span-3 text-center text-gray-400 dark:text-gray-500 py-8">
                        <x-heroicon-o-map class="w-10 h-10 mx-auto mb-2 text-pink-300" />
                        No artworks found.
                    </div>
                @endif
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
