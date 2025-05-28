<?php

namespace App\Filament\Freelancer\Resources\CertificateApplicationResource\Pages;

use App\Filament\Freelancer\Resources\CertificateApplicationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCertificateApplication extends CreateRecord
{
    protected static string $resource = CertificateApplicationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = \Illuminate\Support\Facades\Auth::user()->id;
        return $data;
    }

    protected static bool $canCreateAnother = false;
}
