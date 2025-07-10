<?php

namespace App\Filament\Freelancer\Widgets;

use App\Models\Application;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class ApplicationStatusPieWidget extends ChartWidget
{
    protected static ?string $heading = 'Applications Status';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 2;

    public ?string $filter = null;

    protected function getFilters(): ?array
    {
        $years = range(now()->year, now()->year - 5);
        $months = [
            '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
            '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
            '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December',
        ];
        $filters = ['all' => 'All Time'];
        foreach ($years as $year) {
            foreach ($months as $num => $name) {
                $filters["$year-$num"] = "$name $year";
            }
        }
        return $filters;
    }

    protected function getData(): array
    {
        $user = Auth::user();
        $statuses = ['pending', 'accepted', 'rejected'];
        $counts = [];
        $filter = $this->filter ?? 'all';
        if ($filter === 'all') {
            foreach ($statuses as $status) {
                $counts[] = Application::where('user_id', $user->id)
                    ->where('status', $status)
                    ->count();
            }
        } else {
            [$year, $month] = explode('-', $filter);
            foreach ($statuses as $status) {
                $counts[] = Application::where('user_id', $user->id)
                    ->where('status', $status)
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->count();
            }
        }
        return [
            'datasets' => [
                [
                    'data' => $counts,
                    'backgroundColor' => ['#fbbf24', '#22c55e', '#ef4444'],
                ],
            ],
            'labels' => ['Pending', 'Accepted', 'Rejected'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'color' => '#374151', // Tailwind gray-700
                        'font' => [
                            'size' => 14,
                            'weight' => 'bold',
                        ],
                    ],
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
            'animation' => [
                'animateRotate' => true,
                'animateScale' => true,
            ],
            'scales' => [
                'x' => [
                    'display' => false,
                ],
                'y' => [
                    'display' => false,
                ],
            ],
        ];
    }
}
