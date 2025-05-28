<?php

namespace App\Filament\Admin\Resources\CertificateApplicationResource\Pages;

use App\Filament\Admin\Resources\CertificateApplicationResource;
use Filament\Resources\Pages\EditRecord;

class EditCertificateApplication extends EditRecord
{
    protected static string $resource = CertificateApplicationResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($this->record->status === 'rejected') {
            $data['admin_comment'] = request()->input('admin_comment', '');
        }

        return $data;
    }
}
