<?php

namespace App\Filament\Admin\Pages;

use Filament\Widgets\AccountWidget;
use App\Filament\Admin\Widgets\StatsOverview;
use App\Filament\Admin\Widgets\ArtworksChart;
use App\Filament\Admin\Widgets\SystemOverviewWidget;
use App\Filament\Admin\Widgets\PendingActionsWidget;
use App\Filament\Admin\Widgets\UserGrowthChartWidget;
use App\Filament\Admin\Widgets\JobActivityChartWidget;
use App\Filament\Admin\Widgets\RecentActivityWidget;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $title = 'Admin Dashboard';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Freelancer\Widgets\VisionistBrandWidget::class,
            AccountWidget::class,
            SystemOverviewWidget::class,
            UserGrowthChartWidget::class,
            JobActivityChartWidget::class,
            RecentActivityWidget::class,
        ];
    }
}
