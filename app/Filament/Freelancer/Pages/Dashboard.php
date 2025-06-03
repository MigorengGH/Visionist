<?php

namespace App\Filament\Freelancer\Pages;

use App\Filament\Freelancer\Widgets\MyJobsStats;
use App\Filament\Freelancer\Widgets\MyStatsOverview;
use App\Filament\Freelancer\Widgets\TopLikedArtworks;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use App\Filament\Freelancer\Widgets\MyAcceptedJobsStats;
use App\Filament\Freelancer\Widgets\TopOpenJobsByBudget;


class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $title = 'Dashboard';

    public function getWidgets(): array
    {
        return [
            MyStatsOverview::class,
            MyJobsStats::class,
            MyAcceptedJobsStats::class,
            TopOpenJobsByBudget::class,
            TopLikedArtworks::class,
        ];
    }
}
