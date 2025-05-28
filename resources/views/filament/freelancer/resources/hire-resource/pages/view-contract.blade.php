<x-filament-panels::page>
    <div class="space-y-8">
        <div class="p-8 bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800">
            <div class="mb-8 border-b pb-4 text-center relative">
                <img src="/storage/logoV/logo.svg" alt="Logo" class="mx-auto mb-4" style="max-width: 180px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">
                <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1 tracking-tight">HIRE CONTRACT AGREEMENT</div>
                <div class="text-base text-gray-500 dark:text-gray-300 font-semibold uppercase tracking-wider mb-2">Professional Service Agreement</div>
                <div class="absolute left-1/2 bottom-0 w-24 h-1 bg-gradient-to-r from-blue-500 to-green-500" style="transform: translateX(-50%);"></div>
            </div>

            <div class="mb-8">
                <div class="text-l font-semibold text-gray-900 dark:text-white mb-4 border-b pb-2">1. Parties</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <div class="font-semibold text-gray-700 dark:text-gray-200 mb-2">Client Information</div>
                        <div class="mb-2">
                            <div class="text-xs text-gray-500 dark:text-gray-400">Name</div>
                            <div class="text-base font-medium text-gray-900 dark:text-white">{{ $record->client->name }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Email</div>
                            <div class="text-base font-medium text-gray-900 dark:text-white">{{ $record->client->email }}</div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                        <div class="font-semibold text-gray-700 dark:text-gray-200 mb-2">Freelancer Information</div>
                        <div class="mb-2">
                            <div class="text-xs text-gray-500 dark:text-gray-400">Name</div>
                            <div class="text-base font-medium text-gray-900 dark:text-white">{{ $record->freelancer->name }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Email</div>
                            <div class="text-base font-medium text-gray-900 dark:text-white">{{ $record->freelancer->email }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <div class="text-xl font-semibold text-gray-900 dark:text-white mb-4 border-b pb-2">2. Project Details</div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Project Title</div>
                        <div class="text-base font-medium text-gray-900 dark:text-white">{{ $record->title }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Location</div>
                        <div class="text-base font-medium text-gray-900 dark:text-white">
                            @if($record->is_online)
                                Online Job
                            @else
                                {{ optional($record->state)->name }}, {{ optional($record->district)->name }}
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Agreed Price</div>
                        <div class="text-base font-medium text-gray-900 dark:text-white">MYR {{ number_format($record->price, 2) }}</div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Project Description</div>
                    <div class="text-base font-medium text-gray-900 dark:text-white">{!! $record->description !!}</div>
                </div>
            </div>

            <div class="mb-8">
                <div class="text-xl font-semibold text-gray-900 dark:text-white mb-4 border-b pb-2">3. Terms and Conditions</div>
                <ul class="list-none space-y-2">
                    <li class="flex items-start"><span class="mr-2 mt-1 text-green-500">&#9679;</span> <span class="text-gray-700 dark:text-gray-200">The Freelancer agrees to complete the work as specified in the project description.</span></li>
                    <li class="flex items-start"><span class="mr-2 mt-1 text-green-500">&#9679;</span> <span class="text-gray-700 dark:text-gray-200">The Client agrees to pay the agreed amount upon satisfactory completion of the work.</span></li>
                    <li class="flex items-start"><span class="mr-2 mt-1 text-green-500">&#9679;</span> <span class="text-gray-700 dark:text-gray-200">Both parties agree to maintain confidentiality regarding project details.</span></li>
                    <li class="flex items-start"><span class="mr-2 mt-1 text-green-500">&#9679;</span> <span class="text-gray-700 dark:text-gray-200">Any disputes will be resolved through mutual discussion and agreement.</span></li>
                    <li class="flex items-start"><span class="mr-2 mt-1 text-green-500">&#9679;</span> <span class="text-gray-700 dark:text-gray-200">The project timeline will be determined by mutual agreement between both parties.</span></li>
                    <li class="flex items-start"><span class="mr-2 mt-1 text-green-500">&#9679;</span> <span class="text-gray-700 dark:text-gray-200">Any modifications to the project scope must be agreed upon in writing by both parties.</span></li>
                    <li class="flex items-start"><span class="mr-2 mt-1 text-green-500">&#9679;</span> <span class="text-gray-700 dark:text-gray-200">Both parties agree to maintain professional communication throughout the project duration.</span></li>
                    <li class="flex items-start"><span class="mr-2 mt-1 text-green-500">&#9679;</span> <span class="text-gray-700 dark:text-gray-200">The Freelancer will provide regular updates on the project progress.</span></li>
                </ul>
            </div>

            <div class="text-center text-xs text-gray-500 dark:text-gray-400 mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">
                This contract was generated on {{ now()->format('F j, Y \a\t g:i A') }}<br>
                Contract ID: {{ $record->id }}
            </div>
        </div>
    </div>
</x-filament-panels::page>
