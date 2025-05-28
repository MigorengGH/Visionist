<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Workshop;


class AdminStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalUsers = User::where('isAdmin', 0)->count(); // Exclude admins
        $totalWorkshops = Workshop::count();
        $totalPublishedWorkshops = Workshop::where('publish', '1')->count();
        $totalPricePublishedWorkshops = Workshop::where('publish', '1')->sum('price');

        return [
            Stat::make('Total Users', $totalUsers)
                ->description('Registered users (excluding admins)')
                ->icon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Published Workshops', "$totalPublishedWorkshops / $totalWorkshops")
                ->description('Published / Total workshops')
                ->icon('heroicon-m-cube-transparent')
                ->color('success'),

            Stat::make('Total Price of Published Workshops', 'RM ' . number_format($totalPricePublishedWorkshops, 2))
                ->description('Total earnings from published workshops')
                ->icon('heroicon-m-currency-dollar')
                ->color('warning'),
        ];
    }
}
