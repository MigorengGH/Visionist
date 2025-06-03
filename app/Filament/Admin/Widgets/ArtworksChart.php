<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Artwork;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ArtworksChart extends ChartWidget
{
    protected static ?string $heading = 'Artworks Created Over Time';

    protected function getData(): array
    {
        $data = Artwork::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as total')
        )
            ->groupBy('date')
            ->orderBy('date')
            ->limit(7)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Artworks Created',
                    'data' => $data->pluck('total')->toArray(),
                    'borderColor' => '#10B981',
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
