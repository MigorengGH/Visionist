<?php

namespace App\Filament\Freelancer\Resources\HireResource\Pages;

use App\Filament\Freelancer\Resources\HireResource;
use Filament\Resources\Pages\Page;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Hire;

class ViewContract extends Page
{
    protected static string $resource = HireResource::class;

    protected static string $view = 'filament.freelancer.resources.hire-resource.pages.view-contract';

    public ?Hire $record = null;

    public function mount(Hire $record): void
    {
        $this->record = $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download')
                ->label('Download Contract')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function () {
                    $pdf = Pdf::loadView('contracts.hire-contract', [
                        'hire' => $this->record,
                        'client' => $this->record->client,
                        'freelancer' => $this->record->freelancer,
                    ]);

                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, 'contract-' . $this->record->id . '.pdf');
                }),
        ];
    }
}
