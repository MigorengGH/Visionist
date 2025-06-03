<?php

namespace App\Filament\Freelancer\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class MyStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();

        return [
            Stat::make('My Artworks', $user->artworks()->count())
                ->description('Total artworks created')
                ->descriptionIcon('heroicon-m-photo')
                ->color('success'),

            Stat::make('Total Likes', $user->artworks()->withCount('likes')->get()->sum('likes_count'))
                ->description('Likes received')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),

            Stat::make('Jobs Posted', $user->makejobs()->count())
                ->description('Jobs created')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('primary'),

            Stat::make('Jobs Accepted', $user->applications()->where('status', 'Accepted')->count())
                ->description('Successfully completed')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
