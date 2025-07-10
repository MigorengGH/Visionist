<x-filament-widgets::widget>
    <x-filament::section>
        <div class="w-full">
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-2">
                    <div>
                        <h3 class="text-lg font-bold">My Job Statistics</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Overview of your job performance</p>
                    </div>
                    {{ $this->form }}
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Total Jobs</div>
                                <div class="text-2xl font-bold">{{ $this->getStats()['total_jobs'] }}</div>
                            </div>
                            <div class="p-2 bg-primary-50 dark:bg-primary-900/20 rounded-full">
                                <x-heroicon-o-briefcase class="w-6 h-6 text-primary-500" />
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Open Jobs</div>
                                <div class="text-2xl font-bold text-amber-500">{{ $this->getStats()['open_jobs'] }}</div>
                            </div>
                            <div class="p-2 bg-amber-50 dark:bg-amber-900/20 rounded-full">
                                <x-heroicon-o-clock class="w-6 h-6 text-amber-500" />
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Total Pending Applications</div>
                                <div class="text-2xl font-bold text-primary-500">{{ $this->getStats()['pending_applications'] }}</div>
                            </div>
                            <div class="p-2 bg-primary-50 dark:bg-primary-900/20 rounded-full">
                                <x-heroicon-o-chart-bar class="w-6 h-6 text-primary-500" />
                            </div>
                        </div>
                    </div>

                    {{-- VS BAR: Budget vs Spent --}}
                    <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 flex flex-col justify-between col-span-2">
                        <div class="flex items-center justify-center gap-6">
                            @php
                                $budget = $this->getStats()['total_budget'];
                                $proposed = $this->getStats()['total_proposed_price'];
                                $isGood = $budget >= $proposed;
                            @endphp
                            <div class="flex flex-col items-center">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Total Budget (Accepted)</div>
                                <div class="text-2xl font-bold {{ $isGood ? 'text-success-600' : 'text-danger-600' }}">MYR {{ number_format($budget, 2) }}</div>
                            </div>
                            <div class="mx-4 text-lg font-extrabold {{ $isGood ? 'text-success-600' : 'text-danger-600' }}">VS</div>
                            <div class="flex flex-col items-center">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Total Spent to Freelancer</div>
                                <div class="text-2xl font-bold {{ $isGood ? 'text-success-600' : 'text-danger-600' }}">MYR {{ number_format($proposed, 2) }}</div>
                            </div>
                        </div>
                        <div class="mt-2 text-center">
                            @if($isGood)
                                <span class="inline-block px-2 py-1 text-xs font-semibold bg-success-100 text-success-700 rounded">Good: Budget covers or exceeds spending</span>
                            @else
                                <span class="inline-block px-2 py-1 text-xs font-semibold bg-danger-100 text-danger-700 rounded">Warning: Spending exceeds budget</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
