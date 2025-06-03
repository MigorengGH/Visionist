<div class="space-y-4">
    <div>
        <div class="font-semibold text-gray-700 dark:text-gray-200">Proposed Price</div>
        <div class="text-gray-900 dark:text-gray-100">MYR {{ number_format($record->proposed_price, 2) }}</div>
    </div>
    <div>
        <div class="font-semibold text-gray-700 dark:text-gray-200">Cover Letter</div>
        <div class="prose max-w-none dark:prose-invert">{!! $record->cover_letter !!}</div>
    </div>
    <div>
        <div class="font-semibold text-gray-700 dark:text-gray-200">Supporting Document</div>
        @if($record->supporting_documents)
            <a href="{{ asset('storage/' . $record->supporting_documents) }}" target="_blank" class="text-primary-600 hover:underline">View / Download</a>
        @else
            <span class="text-gray-500">No document uploaded.</span>
        @endif
    </div>
</div>
