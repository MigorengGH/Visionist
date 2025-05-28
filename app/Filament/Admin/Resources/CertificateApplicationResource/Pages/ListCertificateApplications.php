<?php

namespace App\Filament\Admin\Resources\CertificateApplicationResource\Pages;

use App\Filament\Admin\Resources\CertificateApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\CertificateApplication;


class ListCertificateApplications extends ListRecords
{
    protected static string $resource = CertificateApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
{
    return [
        'All' => Tab::make()->badge(CertificateApplication::count()),

        'Pending' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending'))
            ->badge(CertificateApplication::where('status', 'pending')->count())
            ->icon('heroicon-o-clock')
            ,

        'Reapply' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'reapply'))
            ->badge(CertificateApplication::where('status', 'reapply')->count())
            ->icon('heroicon-o-arrow-path'),

        'Approved' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'approved'))
            ->badge(CertificateApplication::where('status', 'approved')->count())
            ->icon('heroicon-o-check'),

        'Rejected' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'rejected'))
            ->badge(CertificateApplication::where('status', 'rejected')->count())
            ->icon('heroicon-o-x-circle'),
    ];
}
}
