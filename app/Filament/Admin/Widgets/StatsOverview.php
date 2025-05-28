<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use App\Models\Artwork;
use App\Models\Makejob;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Total Artworks', Artwork::count())
                ->description('Artworks created')
                ->descriptionIcon('heroicon-m-photo')
                ->color('primary'),

            Stat::make('Total Jobs', Makejob::count())
                ->description('Jobs posted')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('warning'),

            Stat::make('Active Jobs', Makejob::where('status', 'open')->count())
                ->description('Currently open')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
