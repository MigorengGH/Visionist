<x-filament::widget>
    <x-filament::card class="flex flex-row items-center gap-4 p-4">
        <div class="flex items-center justify-center gap-4">
            <img src="{{ $logo }}" alt="Visionist Logo" class="h-11 w-12 object-contain" />
            <span class="text-center text-lg font-semibold">{{ $label }}</span>
        </div>
    </x-filament::card>
</x-filament::widget>
