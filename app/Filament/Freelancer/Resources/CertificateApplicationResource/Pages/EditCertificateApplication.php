<?php

namespace App\Filament\Freelancer\Resources\CertificateApplicationResource\Pages;

use App\Filament\Freelancer\Resources\CertificateApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class EditCertificateApplication extends EditRecord
{
    protected static string $resource = CertificateApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
    return $this->getResource()::getUrl('index');
    }

    protected function authorizeAccess(): void
    {
        if ($this->record->user_id !== Auth::id()) {
            Notification::make()
                ->title('Unauthorized')
                ->body('You can only edit your own certificates.')
                ->danger()
                ->send();

            $this->redirect(CertificateApplicationResource::getUrl('index'));
        }
    }
}
