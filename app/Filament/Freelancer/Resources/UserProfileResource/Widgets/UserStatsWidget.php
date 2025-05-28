<?php

namespace App\Filament\Freelancer\Resources\UserProfileResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsWidget extends BaseWidget
{
    public $record;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Artworks', $this->record->artworks()->count())
                ->description('Artworks created')
                ->descriptionIcon('heroicon-m-photo')
                ->color('success'),

            Stat::make('Total Likes', $this->record->artworks()->withCount('likes')->get()->sum('likes_count'))
                ->description('Total likes received')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),

            Stat::make('Total Earnings', 'MYR ' . $this->record->applications()->where('status', 'accepted')->sum('proposed_price'))
                ->description('Total earnings accepted jobs')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('primary'),

            Stat::make('Jobs Dealed', $this->record->makejobs()->where('status', 'deal')->count())
                ->description('Successfully ')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
