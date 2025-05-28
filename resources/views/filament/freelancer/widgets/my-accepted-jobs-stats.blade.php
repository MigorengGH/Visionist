<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold">My Application Jobs</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Overview of your job applications</p>
                </div>
                {{ $this->form }}
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Total Accepted</div>
                            <div class="text-2xl font-bold text-primary-500">{{ $this->getStats()['total_accepted'] }}</div>
                        </div>
                        <div class="p-2 bg-primary-50 dark:bg-primary-900/20 rounded-full">
                            <x-heroicon-o-check-badge class="w-6 h-6 text-primary-500" />
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Pending</div>
                            <div class="text-2xl font-bold text-amber-500">{{ $this->getStats()['pending'] }}</div>
                        </div>
                        <div class="p-2 bg-amber-50 dark:bg-amber-900/20 rounded-full">
                            <x-heroicon-o-clock class="w-6 h-6 text-amber-500" />
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Rejected</div>
                            <div class="text-2xl font-bold text-rose-500">{{ $this->getStats()['rejected'] }}</div>
                        </div>
                        <div class="p-2 bg-rose-50 dark:bg-rose-900/20 rounded-full">
                            <x-heroicon-o-x-circle class="w-6 h-6 text-rose-500" />
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Accepted Rate</div>
                            <div class="text-2xl font-bold text-primary-500">{{ $this->getStats()['accepted_rate'] }}%</div>
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
                            <div class="text-sm text-gray-500 dark:text-gray-400">Total Earnings</div>
                            <div class="text-2xl font-bold">MYR {{ number_format($this->getStats()['total_earnings'], 2) }}</div>
                        </div>
                        <div class="p-2 bg-primary-50 dark:bg-primary-900/20 rounded-full">
                            <x-heroicon-o-banknotes class="w-6 h-6 text-primary-500" />
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Average Earnings</div>
                            <div class="text-2xl font-bold">MYR {{ number_format($this->getStats()['avg_earnings'], 2) }}</div>
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
