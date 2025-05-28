<x-filament-panels::page>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold">Job Contract</h2>
            <a href="{{ route('filament.freelancer.resources.myjobs.download-contract', $record) }}"
               class="filament-button filament-button-size-lg inline-flex items-center justify-center py-2 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700">
                <x-heroicon-s-arrow-down-tray class="w-5 h-5" />
                Download Contract
            </a>
        </div>

        <div class="bg-white rounded-xl shadow">
            <iframe src="data:application/pdf;base64,{{ base64_encode($pdf) }}"
                    class="w-full h-[800px]"
                    frameborder="0">
            </iframe>
        </div>
    </div>
</x-filament-panels::page>
