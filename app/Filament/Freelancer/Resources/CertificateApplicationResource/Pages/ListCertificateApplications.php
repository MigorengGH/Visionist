<?php

namespace App\Filament\Freelancer\Resources\CertificateApplicationResource\Pages;

use App\Filament\Freelancer\Resources\CertificateApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCertificateApplications extends ListRecords
{
    protected static string $resource = CertificateApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
