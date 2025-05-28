<x-filament-panels::page>
    <div class="space-y-6">
        <div class="p-6 bg-white dark:bg-gray-900 rounded-lg shadow">
            <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Contract Details</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Job Information</h3>
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Title</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $record->title }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Agreement Price</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                @php
                                    $acceptedApplication = $record->applications()->where('status', 'accepted')->first();
                                @endphp
                                @if($acceptedApplication)
                                    MYR {{ number_format($acceptedApplication->proposed_price, 2) }}
                                @else
                                    -
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Location</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                @if($record->is_online)
                                    Online Job
                                @else
                                    {{ optional($record->district)->name }}, {{ optional($record->state)->name }}
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>

                {{-- <div>
                    <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Client Information</h3>
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $record->user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $record->user->email }}</dd>
                        </div>
                    </dl>
                </div> --}}

                <div>
                    <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Freelancer Information</h3>
                    <dl class="space-y-2">
                        @php
                            $acceptedApplication = $record->applications()->where('status', 'accepted')->first();
                            $freelancer = $acceptedApplication ? $acceptedApplication->user : null;
                        @endphp
                        @if($freelancer)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $freelancer->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $freelancer->email }}</dd>
                        </div>
                        @else
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Freelancer</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">No accepted freelancer yet.</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Job Description</h3>
                <div class="prose max-w-none dark:prose-invert">
                    {!! $record->description !!}
                </div>
            </div>
            @php
                $acceptedApplication = $record->applications()->where('status', 'accepted')->first();
            @endphp
            @if($acceptedApplication)
            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Freelancer Cover Letter</h3>
                <div class="prose max-w-none dark:prose-invert">
                    {!! $acceptedApplication->cover_letter !!}
                </div>
            </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
