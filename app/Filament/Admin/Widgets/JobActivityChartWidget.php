<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Makejob;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class JobActivityChartWidget extends ChartWidget
{
    public function getHeading(): string
    {
        return 'Job Posting Activity (Last 30 Days)';
    }

    protected function getData(): array
    {
        $data = Makejob::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as total')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jobs Posted',
                    'data' => $data->pluck('total')->toArray(),
                    'borderColor' => '#F59E0B',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $data->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
