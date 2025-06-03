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

            //total being hired
            Stat::make('Accepted Hired', $this->record->hires()->where('status', 'accepted')->count())
                ->description('Total hired jobs accepetd as freelancer')
                ->descriptionIcon('heroicon-m-fire')
                ->color('primary'),

            // Count accepted applications for freelancer
            Stat::make('Accepted Jobs', $this->record->applications()->where('status', 'accepted')->count())
                ->description('Total jobs accepted as freelancer')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
