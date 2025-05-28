<?php

namespace App\Filament\Freelancer\Resources;

use App\Filament\Freelancer\Resources\CertificateApplicationResource\Pages;
use App\Models\CertificateApplication;
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

class CertificateApplicationResource extends Resource
{
    protected static ?string $model = CertificateApplication::class;

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Detail of Application')
                    ->description('Application can only reapply ONCE')
                    ->headerActions([
                        InfolistAction::make('reapply')
                            ->label('Reapply')
                            ->icon('heroicon-o-arrow-path')
                            ->form([
                                TextInput::make('title')
                                    ->label('Title')
                                    ->required()
                                    ->prefixIcon('heroicon-o-document-text'),
                                ToggleButtons::make('type')
                                    ->inline()
                                    ->required()
                                    ->options([
                                        'institute' => 'Institute/University',
                                        'other' => 'Industry/Other',
                                    ])
                                    ->icons([
                                        'institute' => 'heroicon-o-academic-cap',
                                        'other' => 'heroicon-o-briefcase',
                                    ])
                                    ->colors([
                                        'institute' => 'success',
                                        'other' => 'gray',
                                    ]),
                                FileUpload::make('cv_path')
                                    ->label('Upload CV')
                                    ->openable()
                                    ->required()
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->directory('cvs')
                                    ->visibility('public')
                                    ->preserveFilenames()
                                    ->deleteUploadedFileUsing(function ($file) {
                                        if ($file && Storage::disk('public')->exists($file)) {
                                            Storage::disk('public')->delete($file);
                                        }
                                    }),
                            ])
                            ->action(function (CertificateApplication $record, array $data) {
                                if ($record->reapply_count < 1) {
                                    if ($record->cv_path && Storage::disk('public')->exists($record->cv_path)) {
                                        Storage::disk('public')->delete($record->cv_path);
                                    }

                                    $updateData = [
                                        'title' => $data['title'],
                                        'type' => $data['type'],
                                        'cv_path' => $data['cv_path'],
                                        'status' => 'reapply',
                                        'reapply_count' => $record->reapply_count + 1,
                                    ];

                                    Log::info('Reapply Update Data (Infolist)', $updateData);
                                    $record->update($updateData);

                                    \Filament\Notifications\Notification::make()
                                        ->title('Reapplication Submitted')
                                        ->success()
                                        ->send();
                                }
                            })
                            ->visible(fn (CertificateApplication $record) => $record->status === 'rejected' && $record->reapply_count < 1),
                    ])
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Applicant')
                            ->icon('heroicon-o-user')
                            ->iconColor('primary'),

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
                            ->label('Approved by')
                            ->icon('heroicon-o-user')
                            ->iconColor('success')
                            ->visible(fn (CertificateApplication $record) => $record->status === 'approved'),
                        Fieldset::make('Rejection Comment')
                        ->visible(fn (CertificateApplication $record) => $record->status === 'rejected' || $record->status === 'reapply')
                            ->schema([
                            TextEntry::make('admin_comment')
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
                            ->columnSpanFull(),
                    ])->grow(true)->columnSpan(2),

            ])->columns(5);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                ComponentsSection::make('')
                    ->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->prefixIcon('heroicon-o-document-text')
                            ->columnSpan(4),
                        ToggleButtons::make('type')
                            ->inline()
                            ->columnSpan(2)
                            ->required()
                            ->options([
                                'institute' => 'Institute/University',
                                'other' => 'Industry/Other',
                            ])
                            ->icons([
                                'institute' => 'heroicon-o-academic-cap',
                                'other' => 'heroicon-o-briefcase',
                            ])
                            ->colors([
                                'institute' => 'success',
                                'other' => 'gray',
                            ]),
                        FileUpload::make('cv_path')
                            ->label('Upload CV')
                            ->openable()
                            ->required()
                            ->acceptedFileTypes(['application/pdf'])
                            ->directory('cvs')
                            ->visibility('public')
                            ->preserveFilenames()
                            ->deleteUploadedFileUsing(function ($file) {
                                if ($file && Storage::disk('public')->exists($file)) {
                                    Storage::disk('public')->delete($file);
                                }
                            })->columnSpanFull(),
                        FFs::make('Terms & Service')
                            ->schema([
                                Toggle::make('terms_of_service')
                                    ->label('By applying, you agree to our verification process, you are unable to edit the application after submitted; false information may lead to rejection or revocation.')
                                    ->accepted()
                                    ->onColor('success')
                                    ->offColor('danger')
                            ])->columnSpan(6)->columns(1)
                    ])->columnSpan(6)->columns(6),
            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->url(fn ($record) => asset('storage/' . $record->cv_path), true)
                    ->openUrlInNewTab(false)
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => $state === 'institute' ? 'Institute/University' : 'Industry/Other')
                    ->icon(fn ($state) => $state === 'institute' ? 'heroicon-o-academic-cap' : 'heroicon-o-briefcase')
                    ->color(fn ($state) => $state === 'institute' ? 'success' : 'gray')
                    ->badge()
                    ->sortable(),
                TextColumn::make('status')
                    ->icon(fn (string $state): string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'approved' => 'heroicon-o-check',
                        'rejected' => 'heroicon-o-x-circle',
                        'reapply' => 'heroicon-o-arrow-path',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'primary',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'reapply' => 'info',
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Applied at ')
                    ->dateTime(),
            ])
            ->filters([])
            ->actions([

                Tables\Actions\ViewAction::make()
                ->label('View')
                ->icon('heroicon-o-eye')
                ->color('gray'),
                TableAction::make('reapply')
                    ->label('Reapply')
                    ->icon('heroicon-o-arrow-path')
                    ->form([
                        TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->prefixIcon('heroicon-o-document-text'),
                        ToggleButtons::make('type')
                            ->inline()
                            ->required()
                            ->options([
                                'institute' => 'Institute/University',
                                'other' => 'Industry/Other',
                            ])
                            ->icons([
                                'institute' => 'heroicon-o-academic-cap',
                                'other' => 'heroicon-o-briefcase',
                            ])
                            ->colors([
                                'institute' => 'success',
                                'other' => 'gray',
                            ]),
                        FileUpload::make('cv_path')
                            ->label('Upload New CV')
                            ->openable()
                            ->required()
                            ->acceptedFileTypes(['application/pdf'])
                            ->directory('cvs')
                            ->visibility('public')
                            ->preserveFilenames()
                            ->deleteUploadedFileUsing(function ($file) {
                                if ($file && Storage::disk('public')->exists($file)) {
                                    Storage::disk('public')->delete($file);
                                }
                            }),
                    ])
                    ->action(function (CertificateApplication $record, array $data) {
                        if ($record->reapply_count < 1) {
                            if ($record->cv_path && Storage::disk('public')->exists($record->cv_path)) {
                                Storage::disk('public')->delete($record->cv_path);
                            }

                            $updateData = [
                                'title' => $data['title'],
                                'type' => $data['type'],
                                'cv_path' => $data['cv_path'],
                                'status' => 'reapply',
                                'reapply_count' => $record->reapply_count + 1,
                            ];

                            Log::info('Reapply Update Data', $updateData);
                            $record->update($updateData);

                            \Filament\Notifications\Notification::make()
                                ->title('Reapplication Submitted')
                                ->success()
                                ->send();
                        }
                    })
                    ->visible(fn (CertificateApplication $record) => $record->status === 'rejected' && $record->reapply_count < 1),
            ])
            ->bulkActions([])
            ->modifyQueryUsing(fn ($query) => $query->where('user_id', Auth::id()))
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCertificateApplications::route('/'),
            'create' => Pages\CreateCertificateApplication::route('/create'),
            'view' => Pages\ViewCertificateApplication::route('/{record}'),
        ];
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::id());
    }
}
