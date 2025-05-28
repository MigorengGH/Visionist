<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CertificateApplicationResource\Pages;
use App\Filament\Admin\Resources\CertificateApplicationResource\RelationManagers;
use App\Models\CertificateApplication;
use Dom\Text;
use Filament\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section as ComponentsSection;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Facades\Log;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists\Components\Actions\Action as InfolistAction;
use Filament\Infolists\Components\Fieldset;
use Filament\Forms\Components\Fieldset as FFs;
use Joaopaulolndev\FilamentPdfViewer\Infolists\Components\PdfViewerEntry;
use Filament\Support\Enums\FontFamily;
use ZipArchive;
use Filament\Tables\Actions\ActionGroup as TablesActionGroup;

class CertificateApplicationResource extends Resource
{
    protected static ?string $model = CertificateApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-badge';

    protected static ?string $navigationBadgeTooltip = 'The number of users';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Detail of Application')
                ->description(fn (CertificateApplication $record) => 'Applied by ' . ($record->user?->name))
                    ->schema([

                        TextEntry::make('title')
                            ->iconColor('primary')
                            ->icon('heroicon-o-document-text'),
                        TextEntry::make('type')
                            ->formatStateUsing(fn ($state) => $state === 'institute' ? 'Institute/University' : 'Industry/Other')
                            ->icon(fn ($state) => $state === 'institute' ? 'heroicon-o-academic-cap' : 'heroicon-o-briefcase')
                            ->iconColor(fn ($state) => $state === 'institute' ? 'success' : 'gray'),
                        TextEntry::make('created_at')
                            ->label('Applied at ')
                            ->icon('heroicon-o-calendar')
                            ->dateTime(),
                        TextEntry::make('status')
                        ->icon(fn (string $state): string => match ($state) {
                            'pending' => 'heroicon-o-clock',
                            'approved' => 'heroicon-o-check',
                            'rejected' => 'heroicon-o-x-circle',
                            'reapply' => 'heroicon-o-arrow-path',
                        })
                        ->iconColor(fn (string $state): string => match ($state) {
                            'pending' => 'primary',
                            'approved' => 'success',
                            'rejected' => 'danger',
                            'reapply' => 'info',
                        }),
                        TextEntry::make('approvedBy.name')
                            ->label('Approve by')
                            ->icon('heroicon-o-user')
                            ->iconColor('success')
                            ->visible(fn (CertificateApplication $record) => $record->status === 'approved'),
                        Fieldset::make('Rejection Comment')
                        ->visible(fn (CertificateApplication $record) => $record->status === 'rejected' || $record->status === 'reapply')
                            ->schema([
                            TextEntry::make('admin_comment')
                            ->columnSpanFull()
                            ->fontFamily(FontFamily::Mono)
                            ->label(''),
                            ]),
                    ])->columnSpan(3),

                Section::make('')
                    ->schema([
                        PdfViewerEntry::make('cv_path')
                            ->label('')
                            ->minHeight('60svh')
                            ->fileUrl(fn (CertificateApplication $record) => $record->cv_path ? asset('storage/' . $record->cv_path) : null)
                    ])->grow(true)->columnSpan(2),

            ])->columns(5);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Title')
                    ->url(fn ($record) => asset('storage/' . $record->cv_path), true)
                    ->openUrlInNewTab(false)
                    ->sortable()
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => $state === 'institute' ? 'Institute/University' : 'Industry/Other')
                    ->icon(fn ($state) => $state === 'institute' ? 'heroicon-o-academic-cap' : 'heroicon-o-building-office')
                    ->color(fn ($state) => $state === 'institute' ? 'success' : 'info')
                    ->badge()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->icon(fn (string $state): string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'approved' => 'heroicon-o-check',
                        'rejected' => 'heroicon-o-x-circle',
                        'reapply' => 'heroicon-o-arrow-path',
                    })
                    ->iconColor(fn (string $state): string => match ($state) {
                        'pending' => 'primary',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'reapply' => 'info',
                    })
                    ->sortable(),
                TextColumn::make('approvedBy.name')
                ->label('Approved By')
                ->default('N/A')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([

                Action::make('approve')
                        ->label('Approve')
                        ->color('success')
                        ->icon('heroicon-o-check')
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->update(['status' => 'approved','approved_by' => \Illuminate\Support\Facades\Auth::user()->id]))

                        ->visible(fn ($record) => $record->status === 'pending'|| $record->status === 'reapply'),

                Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->form([
                        Forms\Components\Textarea::make('admin_comment')
                            ->label('Rejection Reason')
                            ->required(),
                    ])
                    ->requiresConfirmation()
                    ->action(fn ($record, array $data) => $record->update([
                        'status' => 'rejected',
                        'admin_comment' => $data['admin_comment'],
                    ]))
                    ->visible(fn ($record) => $record->status === 'pending'|| $record->status === 'reapply'),
                    Tables\Actions\ViewAction::make()
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->color('gray'),
                    TablesActionGroup::make([
                        Action::make('download_cv')
                            ->label('Download CV')
                            ->color('primary')
                            ->icon('heroicon-o-arrow-down-tray')
                            ->action(fn ($record) => response()->download(storage_path('app/public/' . $record->cv_path)))
                            ->visible(fn ($record) => !empty($record->cv_path)),
                        ])
                        ->label('Actions'),

        ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'institute' => 'Institute/University',
                        'other' => 'Industry/Other',

                    ]),
            ])
            // ->actions([
            //     //Tables\Actions\EditAction::make(),
            // ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('download_all_pdfs')
                    ->label('Download All PDFs')
                    ->icon('heroicon-o-folder-arrow-down')
                    ->action(function ($records) {
                        $zipFileName = 'cv_documents.zip';
                        $zipPath = storage_path('app/' . $zipFileName);

                        $zip = new ZipArchive;
                        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                            foreach ($records as $record) {
                                if (!empty($record->cv_path) && Storage::disk('public')->exists($record->cv_path)) {
                                    $filePath = Storage::disk('public')->path($record->cv_path);
                                    $zip->addFile($filePath, basename($filePath));
                                }
                            }
                            $zip->close();
                        }

                        return response()->download($zipPath)->deleteFileAfterSend(true);
                    }),
            ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCertificateApplications::route('/'),
            'view' => Pages\ViewCertificateApplication::route('/{record}'),
            //'create' => Pages\CreateCertificateApplication::route('/create'),
            //'edit' => Pages\EditCertificateApplication::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string{
        return static::getModel()::where('status', 'pending')->count();

    }

    public static function getNavigationBadgeTooltip(): ?string{
        return 'The number of pending application';
    }

    public static function canCreate(): bool
    {
    return false;
    }
}
