<?php

namespace App\Filament\Freelancer\Pages;

use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use App\Filament\Freelancer\Widgets\MyJobsStats;
use App\Filament\Freelancer\Widgets\MyStatsOverview;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use App\Filament\Freelancer\Widgets\MyAcceptedJobsStats;
use App\Filament\Freelancer\Widgets\TopOpenJobsByBudget;
use App\Filament\Freelancer\Widgets\TopLikeArtworkSlider;
use App\Filament\Freelancer\Widgets\ApplicationStatusPieWidget;


class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $title = 'Dashboard';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Freelancer\Widgets\VisionistBrandWidget::class,
            AccountWidget::class,
            TopOpenJobsByBudget::class,
            MyStatsOverview::class,
            \App\Filament\Freelancer\Widgets\TotalEarningChartWidget::class,
            ApplicationStatusPieWidget::class,
            MyJobsStats::class,
        ];
    }
}
