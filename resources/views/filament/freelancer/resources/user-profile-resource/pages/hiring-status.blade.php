<div class="space-y-4">
    @if($hires->isEmpty())
        <div class="text-gray-500 text-center py-4">
            No hiring history found.
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2">Title</th>
                        <th class="px-4 py-2">Price</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hires as $hire)
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-2">{{ $hire->title }}</td>
                            <td class="px-4 py-2">MYR {{ number_format($hire->price, 2) }}</td>
                            <td class="px-4 py-2">
                                <span @class([
                                    'px-2 py-1 text-xs font-medium rounded-full',
                                    'bg-yellow-100 text-yellow-800' => $hire->status === 'pending',
                                    'bg-green-100 text-green-800' => $hire->status === 'accepted',
                                    'bg-red-100 text-red-800' => $hire->status === 'rejected',
                                ])>
                                    {{ ucfirst($hire->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">{{ $hire->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-2">
                                @if($hire->status === 'accepted')
                                    <a href="{{ route('filament.freelancer.resources.hires.view-contract', $hire) }}"
                                       class="text-primary-600 hover:text-primary-500">
                                        View Contract
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
