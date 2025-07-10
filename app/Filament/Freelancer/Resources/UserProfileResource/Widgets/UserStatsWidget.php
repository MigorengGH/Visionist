<?php

namespace App\Filament\Freelancer\Resources\UserProfileResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Application;
use App\Models\Hire;
use App\Models\Makejob;
use Illuminate\Support\Facades\Auth;

class UserStatsWidget extends BaseWidget
{
    public $record;

    protected function getStats(): array
    {
        $user = Auth::user();

        // Monthly earnings from accepted hires
        $hireEarnings = Hire::where('freelancer_id', $user->id)
            ->where('status', 'accepted')
            ->whereYear('created_at', now()->year)
            ->selectRaw('MONTH(created_at) as month, SUM(price) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        // Monthly earnings from accepted applications
        $appEarnings = Application::where('user_id', $user->id)
            ->where('status', 'accepted')
            ->whereYear('created_at', now()->year)
            ->selectRaw('MONTH(created_at) as month, SUM(proposed_price) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        // Combine both
        $monthlyEarnings = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyEarnings[] = ($hireEarnings[$m] ?? 0) + ($appEarnings[$m] ?? 0);
        }

        return [
            Stat::make('Total Earning', function () {
                // Earnings from accepted hires
                $freelancerEarnings = Hire::where('freelancer_id', $this->record->id)
                    ->where('status', 'accepted')
                    ->sum('price');

                // Earnings from accepted applications (sum proposed_price)
                $applicationEarnings = Application::where('user_id', $this->record->id)
                    ->where('status', 'accepted')
                    ->sum('proposed_price');

                return 'MYR ' . number_format($freelancerEarnings + $applicationEarnings, 2);
            })
            ->description('Total earnings from accepted hires and applications')
            ->descriptionIcon('heroicon-m-currency-dollar')
            ->color('success')
            ->chart($monthlyEarnings),

            Stat::make('Total Likes', $this->record->artworks()->withCount('likes')->get()->sum('likes_count'))
                ->description('Total likes received')
                ->descriptionIcon('heroicon-m-heart')
                ->color('danger'),

            //total being hired
            Stat::make('Accepted Hired', $this->record->hires()->where('status', 'accepted')->count())
                ->description('Total hired jobs accepetd as freelancer')
                ->descriptionIcon('heroicon-m-fire')
                ->color('primary'),

            // Count accepted applications for freelancer
            Stat::make('Accepted Jobs Application', $this->record->applications()->where('status', 'accepted')->count())
                ->description('Total jobs accepted as freelancer')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('info'),
        ];
    }
}
