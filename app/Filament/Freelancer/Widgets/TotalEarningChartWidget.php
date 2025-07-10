<?php

namespace App\Filament\Freelancer\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Hire;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Filament\Forms;

class TotalEarningChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Total Earnings (per Month)';
    protected int | string | array $columnSpan = 2;
    public ?string $filter = null;

    protected function getFilters(): ?array
    {
        $years = range(now()->year, now()->year - 5);
        return ['all' => 'All Time'] + array_combine($years, $years);
    }

    protected function getData(): array
    {
        $user = Auth::user();
        $year = $this->filter ?? 'all';
        // Monthly earnings from accepted hires
        $hireQuery = Hire::where('freelancer_id', $user->id)
            ->where('status', 'accepted');
        $appQuery = Application::where('user_id', $user->id)
            ->where('status', 'accepted');
        if ($year !== 'all') {
            $hireQuery->whereYear('created_at', $year);
            $appQuery->whereYear('created_at', $year);
        }
        $hireEarnings = $hireQuery
            ->selectRaw('MONTH(created_at) as month, SUM(price) as total')
            ->groupBy('month')
            ->pluck('total', 'month');
        $appEarnings = $appQuery
            ->selectRaw('MONTH(created_at) as month, SUM(proposed_price) as total')
            ->groupBy('month')
            ->pluck('total', 'month');
        $monthlyEarnings = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyEarnings[] = ($hireEarnings[$m] ?? 0) + ($appEarnings[$m] ?? 0);
        }
        return [
            'datasets' => [
                [
                    'label' => 'Total Earnings',
                    'data' => $monthlyEarnings,
                    'borderColor' => '#f59e42',
                    'backgroundColor' => 'rgba(245, 158, 66, 0.2)',
                ],
                [
                    'label' => 'Hire Earnings',
                    'data' => array_map(fn($m) => $hireEarnings[$m] ?? 0, range(1, 12)),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                ],
                [
                    'label' => 'Application Earnings',
                    'data' => array_map(fn($m) => $appEarnings[$m] ?? 0, range(1, 12)),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.2)',
                ],
            ],
            'labels' => [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            ],
            'maintainAspectRatio' => false,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
