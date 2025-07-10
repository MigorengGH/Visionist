<?php

namespace App\Filament\Freelancer\Widgets;

use App\Models\Makejob;
use Filament\Widgets\Widget;


class TopOpenJobsByBudget extends Widget
{

    protected int | string | array $columnSpan = 2;
    protected static string $view = 'filament.freelancer.widgets.top-open-jobs-by-budget';

    public function getTopJobs()
    {
        return Makejob::where('status', 'open')
            ->orderByDesc('budget')
            ->take(5)
            ->get();
    }
}
