<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col items-center justify-center mb-6">
            <img src="{{ asset('storage/logoV/logo.svg') }}" alt="Logo" class="h-16 mb-4">
            <div class="text-2xl md:text-3xl font-extrabold text-center text-gray-900 dark:text-white mb-1">
                JOB CONTRACT AGREEMENT
            </div>
            <div class="text-base font-semibold text-center text-gray-400 dark:text-gray-300">
                Professional Service Agreement
            </div>
        </div>

        <!-- 1. Parties -->
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow p-6 mb-8">
            <div class="text-xl font-semibold text-gray-900 dark:text-white mb-4 border-b pb-2">1. Parties</div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                    <div class="font-semibold text-gray-700 dark:text-gray-200 mb-2">Client Information</div>
                    <div class="mb-2">
                        <div class="text-xs text-gray-500 dark:text-gray-400">Name</div>
                        <div class="text-base font-medium text-gray-900 dark:text-white">{{ $record->user->name }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Email</div>
                        <div class="text-base font-medium text-gray-900 dark:text-white">{{ $record->user->email }}</div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                    <div class="font-semibold text-gray-700 dark:text-gray-200 mb-2">Freelancer Information</div>
                    @php
                        $acceptedApplication = $record->applications()->where('status', 'accepted')->first();
                        $freelancer = $acceptedApplication ? $acceptedApplication->user : null;
                    @endphp
                    @if($freelancer)
                        <div class="mb-2">
                            <div class="text-xs text-gray-500 dark:text-gray-400">Name</div>
                            <div class="text-base font-medium text-gray-900 dark:text-white">{{ $freelancer->name }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Email</div>
                            <div class="text-base font-medium text-gray-900 dark:text-white">{{ $freelancer->email }}</div>
                        </div>
                    @else
                        <div class="text-base font-medium text-gray-900 dark:text-white">No accepted freelancer yet.</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 2. Project Details -->
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow p-6 mb-8">
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
                            {{ optional($record->district)->name }}, {{ optional($record->state)->name }}
                        @endif
                    </div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Agreed Price</div>
                    <div class="text-base font-medium text-gray-900 dark:text-white">
                        @if($acceptedApplication)
                            MYR {{ number_format($acceptedApplication->proposed_price, 2) }}
                        @else
                            -
                        @endif
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Project Description</div>
                <div class="text-base font-medium text-gray-900 dark:text-white prose max-w-none dark:prose-invert">
                    {!! $record->description !!}
                </div>
            </div>
        </div>

        <!-- 3. Freelancer Cover Letter -->
        @if($acceptedApplication)
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow p-6 mb-8">
            <div class="text-xl font-semibold text-gray-900 dark:text-white mb-4 border-b pb-2">3. Freelancer Cover Letter</div>
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                <div class="text-base font-medium text-gray-900 dark:text-white prose max-w-none dark:prose-invert">
                    {!! $acceptedApplication->cover_letter !!}
                </div>
            </div>
        </div>
        @endif

        <!-- 4. Terms and Conditions -->
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow p-6">
            <div class="text-xl font-semibold text-gray-900 dark:text-white mb-4 border-b pb-2">4. Terms and Conditions</div>
            <ul class="space-y-2">
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
</x-filament-panels::page>
