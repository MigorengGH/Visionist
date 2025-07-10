<?php

namespace App\Filament\Freelancer\Widgets;

use Filament\Widgets\Widget;

class VisionistBrandWidget extends Widget
{
    protected static string $view = 'filament.freelancer.widgets.visionist-brand-widget';
    protected static ?int $sort = 1; // Show after AccountWidget

    protected int | string | array $columnSpan = 1;
    public function getViewData(): array
    {
        return [
            'logo' => asset('storage/logoV/logo.svg'), // Adjust path if needed
            'label' => 'Start Your Vision Here',
        ];
    }
}
