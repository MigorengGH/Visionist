<?php

namespace App\Filament\Freelancer\Resources\HireResource\Pages;

use App\Filament\Freelancer\Resources\HireResource;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadContract extends Page
{
    protected static string $resource = HireResource::class;

    protected static string $view = 'filament.freelancer.resources.hire-resource.pages.download-contract';

    public function mount($record): StreamedResponse
    {
        // Ensure user has access to this contract
        if (Auth::user()->isClient()) {
            abort_if($record->client_id !== Auth::id(), 403);
        } else {
            abort_if($record->freelancer_id !== Auth::id(), 403);
        }

        $pdf = Pdf::loadView('contracts.hire-contract', [
            'hire' => $record,
            'client' => $record->client,
            'freelancer' => $record->freelancer,
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, "contract-{$record->id}.pdf");
    }
}
