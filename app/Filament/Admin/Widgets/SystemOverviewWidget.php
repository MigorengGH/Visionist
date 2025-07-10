<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use App\Models\Artwork;
use App\Models\Makejob;
use App\Models\Workshop;
use App\Models\CertificateApplication;
use App\Models\Application;
use App\Models\Hire;
use App\Models\Chat;
use App\Models\Message;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SystemOverviewWidget extends BaseWidget
{
    protected function getHeading(): string
    {
        return 'System Overview';
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Total Artworks', Artwork::count())
                ->description('Published artworks')
                ->descriptionIcon('heroicon-m-photo')
                ->color('info'),

            Stat::make('Open Jobs', Makejob::where('status', 'open')->count())
                ->description('Available for application')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Active Workshops', Workshop::where('publish', true)->count())
                ->description('Published workshops')
                ->descriptionIcon('heroicon-m-cube-transparent')
                ->color('primary'),

            Stat::make('Certificate Applications', CertificateApplication::count())
                ->description('All Application')
                ->descriptionIcon('heroicon-m-document-check')
                ->color('warning'),

            Stat::make('Pending Certificates', CertificateApplication::where('status', 'pending')->count())
                ->description('Awaiting approval')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'),
        ];
    }
}
