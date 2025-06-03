<?php

namespace App\Filament\Freelancer\Resources\CertificateApplicationResource\Pages;

use App\Filament\Freelancer\Resources\CertificateApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class ViewCertificateApplication extends ViewRecord
{
    protected static string $resource = CertificateApplicationResource::class;

    protected function authorizeAccess(): void
    {
        if ($this->record->user_id !== Auth::id()) {
            Notification::make()
                ->title('Unauthorized')
                ->body('You can only view your own certificates.')
                ->danger()
                ->send();

            $this->redirect(CertificateApplicationResource::getUrl('index'));
        }
    }
}
