<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-bold text-amber-600 dark:text-amber-400 flex items-center">
                    <x-heroicon-o-currency-dollar class="w-6 h-6 mr-2 text-amber-500" /> Top 5 Open Jobs by Budget
                </h3>
            </div>
            <div class="grid grid-cols-1 gap-4">
                @foreach($this->getTopJobs() as $job)
                    <div class="bg-white dark:bg-gray-900 rounded-xl shadow p-4 flex items-center border border-gray-200 dark:border-gray-700 transition hover:shadow-2xl">
                        <div class="flex-1">
                            <div class="font-semibold text-lg text-gray-800 dark:text-gray-100 mb-1">{{ $job->title }}</div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-600 dark:text-white text-xs font-bold">
                                    <x-heroicon-o-currency-dollar class="w-4 h-4 mr-1" /> MYR {{ number_format($job->budget, 2) }}
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-lime-100 text-lime-700 dark:bg-lime-600 dark:text-white text-xs font-bold">
                                    <x-heroicon-o-briefcase class="w-4 h-4 mr-1" /> {{ $job->is_online ? 'Online' : 'Physical' }}
                                </span>
                            </div>
                        </div>
                        <a href="{{ route('filament.freelancer.resources.searchjobs.view', ['record' => $job->id]) }}"
                           class="ml-4 min-w-[80px] min-h-[40px] flex items-center justify-center px-4 py-2 rounded-lg bg-amber-700 hover:bg-amber-800 text-white font-bold border border-amber-800 shadow transition text-base !bg-amber-700 !text-white"
                           style="background-color: #b45309 !important; color: #fff !important;"
                           aria-label="View Job">
                            View
                        </a>
                    </div>
                @endforeach
                @if($this->getTopJobs()->isEmpty())
                    <div class="text-center text-gray-400 dark:text-gray-500 py-8">
                        <x-heroicon-o-briefcase class="w-10 h-10 mx-auto mb-2 text-amber-300" />
                        No open jobs found.
                    </div>
                @endif
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
