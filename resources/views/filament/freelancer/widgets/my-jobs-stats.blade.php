<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold">Job Statistics</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Overview of your job performance</p>
                </div>
                {{ $this->form }}
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
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

                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
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

                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Deal Jobs</div>
                            <div class="text-2xl font-bold text-emerald-500">{{ $this->getStats()['deal_jobs'] }}</div>
                        </div>
                        <div class="p-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-full">
                            <x-heroicon-o-check-circle class="w-6 h-6 text-emerald-500" />
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Total Applications</div>
                            <div class="text-2xl font-bold text-primary-500">{{ $this->getStats()['total_applications'] }}</div>
                        </div>
                        <div class="p-2 bg-primary-50 dark:bg-primary-900/20 rounded-full">
                            <x-heroicon-o-chart-bar class="w-6 h-6 text-primary-500" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Total Budget</div>
                            <div class="text-2xl font-bold">MYR {{ number_format($this->getStats()['total_budget'], 2) }}</div>
                        </div>
                        <div class="p-2 bg-primary-50 dark:bg-primary-900/20 rounded-full">
                            <x-heroicon-o-currency-dollar class="w-6 h-6 text-primary-500" />
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Total Proposal Price</div>
                            <div class="text-2xl font-bold">MYR {{ number_format($this->getStats()['total_proposal_price'], 2) }}</div>
                        </div>
                        <div class="p-2 bg-primary-50 dark:bg-primary-900/20 rounded-full">
                            <x-heroicon-o-calculator class="w-6 h-6 text-primary-500" />
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Online Jobs</div>
                            <div class="text-2xl font-bold text-indigo-500">{{ $this->getStats()['online_jobs'] }}</div>
                        </div>
                        <div class="p-2 bg-indigo-50 dark:bg-indigo-900/20 rounded-full">
                            <x-heroicon-o-computer-desktop class="w-6 h-6 text-indigo-500" />
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Physical Jobs</div>
                            <div class="text-2xl font-bold text-rose-500">{{ $this->getStats()['physical_jobs'] }}</div>
                        </div>
                        <div class="p-2 bg-rose-50 dark:bg-rose-900/20 rounded-full">
                            <x-heroicon-o-map-pin class="w-6 h-6 text-rose-500" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
