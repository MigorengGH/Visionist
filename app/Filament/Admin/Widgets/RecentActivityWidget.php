<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use App\Models\Artwork;
use App\Models\Makejob;
use App\Models\Application;
use App\Models\CertificateApplication;
use App\Models\Workshop;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;

class RecentActivityWidget extends BaseWidget
{
    protected function getHeading(): string
    {
        return 'Recent Freelancer Registrations';
    }

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->where('isAdmin', false)
                    ->where('created_at', '>=', now()->subDays(7))
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('name')
                    ->label('User Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('email_verified_at')
                    ->label('Verified')
                    ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No')
                    ->icons([
                        'heroicon-m-check-circle' => fn ($state) => filled($state),
                        'heroicon-m-x-circle' => fn ($state) => empty($state),
                    ])
                    ->colors([
                        'success' => fn ($state) => filled($state),
                        'danger' => fn ($state) => empty($state),
                    ])
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Registered')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
