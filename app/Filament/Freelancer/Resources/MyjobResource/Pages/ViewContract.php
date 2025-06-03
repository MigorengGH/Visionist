<?php

namespace App\Filament\Freelancer\Resources\MyjobResource\Pages;

use App\Filament\Freelancer\Resources\MyjobResource;
use Filament\Resources\Pages\Page;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\State;
use App\Models\District;
use App\Models\Makejob;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;

class ViewContract extends Page
{
    protected static string $resource = MyjobResource::class;

    protected static string $view = 'filament.freelancer.resources.myjob-resource.pages.view-contract';

    public Makejob $record;

    public function mount(Makejob $record): void
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
                    $acceptedApplication = $this->record->applications()
                        ->where('status', 'accepted')
                        ->first();

                    if (!$acceptedApplication) {
                        Notification::make()
                            ->title('No accepted application found')
                            ->danger()
                            ->send();
                        return;
                    }

                    $pdf = Pdf::loadView('contracts.job-contract', [
                        'job' => $this->record,
                        'client' => $this->record->user,
                        'freelancer' => $acceptedApplication->user,
                        'application' => $acceptedApplication,
                    ]);

                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, "contract-{$this->record->id}.pdf");
                }),
        ];
    }
}
