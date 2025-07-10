<?php

namespace App\Filament\Freelancer\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\Concerns\CanPoll;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MyStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();


        return [
            Stat::make('Total Likes', $user->artworks()->withCount('likes')->get()->sum('likes_count'))
                ->description('Total likes received')
                ->descriptionIcon('heroicon-m-heart')
                ->color('danger'),

            //total being hired
            Stat::make('Accepted Hired', $user->hires()->where('status', 'accepted')->count())
                ->description('Total hired jobs accepted as freelancer')
                ->descriptionIcon('heroicon-m-fire')
                ->color('primary'),

            // Count accepted applications for freelancer
            Stat::make('Accepted Jobs Application', $user->applications()->where('status', 'accepted')->count())
                ->description('Total jobs accepted as freelancer')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('info'),

            // Stat::make('Jobs Accepted', $user->applications()->where('status', 'Accepted')->count())
            //     ->description('Successfully completed')
            //     ->descriptionIcon('heroicon-m-check-circle')
            //     ->color('success'),
        ];
    }

    protected int | string | array $columnSpan = 2;
}
