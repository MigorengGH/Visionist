<?php

namespace App\Filament\Freelancer\Pages\Auth;

use Filament\Forms\Form;
use Filament\Forms\Components\Tabs;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Actions\Action;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class FreelancerEditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('General')
                            ->icon('heroicon-o-user-circle')
                            ->schema([
                                FileUpload::make('avatar')
                                    ->image()
                                    ->avatar()
                                    ->directory('avatars')
                                    ->visibility('public')
                                    ->preserveFilenames()
                                    ->maxSize(5120)
                                    ->columnSpanFull()
                                    ->reorderable(false)
                                    ->imageEditor()
                                    ->deleteUploadedFileUsing(function ($file) {
                                        Storage::disk('public')->delete($file);
                                    })
                                    ->acceptedFileTypes(['image/*']),
                                $this->getNameFormComponent(),
                                TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->disabled()
                                    ->dehydrated(false),
                                TextInput::make('phone')
                                    ->tel()
                                    ->placeholder('+1234567890')
                                    ->minLength(8)
                                    ->maxLength(15),
                                $this->getPasswordFormComponent(),
                                $this->getPasswordConfirmationFormComponent(),
                            ]),
                        Tabs\Tab::make('About Me')
                            ->icon('heroicon-o-identification')
                            ->schema([
                                \Filament\Forms\Components\Textarea::make('aboutme')
                                    ->label('About Me')
                                    ->placeholder('Write a short description about yourself')
                                    ->columnSpanFull(),

                                Select::make('certificate_1')
                                    ->label('Certificate 1')
                                    ->helperText('Select an approved certificate to display on your profile')
                                    ->options(function () {
                                        $userId = Auth::id();
                                        return \App\Models\CertificateApplication::where('user_id', $userId)
                                            ->where('status', 'approved')
                                            ->pluck('title', 'id')
                                            ->toArray();
                                    })
                                    //->live()
                                    ->columnSpanFull(),
                                Select::make('certificate_2')
                                    ->label('Certificate 2')
                                    ->helperText('Select an approved certificate to display on your profile')
                                    ->options(function ($get) {
                                        $userId = Auth::id();
                                        $cert1 = $get('certificate_1');

                                        $query = \App\Models\CertificateApplication::where('user_id', $userId)
                                            ->where('status', 'approved');

                                        if ($cert1) {
                                            $query->where('id', '!=', $cert1);
                                        }

                                        return $query->pluck('title', 'id')->toArray();
                                    })
                                   // ->live()
                                    ->columnSpanFull(),
                                Select::make('certificate_3')
                                    ->label('Certificate 3')
                                    ->helperText('Select an approved certificate to display on your profile')
                                    ->options(function ($get) {
                                        $userId = Auth::id();
                                        $cert1 = $get('certificate_1');
                                        $cert2 = $get('certificate_2');

                                        $query = \App\Models\CertificateApplication::where('user_id', $userId)
                                            ->where('status', 'approved');

                                        if ($cert1) {
                                            $query->where('id', '!=', $cert1);
                                        }

                                        if ($cert2) {
                                            $query->where('id', '!=', $cert2);
                                        }

                                        return $query->pluck('title', 'id')->toArray();
                                    })
                                   // ->live()
                                    ->columnSpanFull(),
                            ]),
                        Tabs\Tab::make('Other')
                            ->icon('heroicon-o-ellipsis-horizontal')
                            ->schema([
                            TextInput::make('instagram')
                                ->label('Instagram Username')
                                ->url()
                                ->placeholder('your_instagram_username')
                                ->prefix('instagram.com/')
                                ->helperText('Enter your Instagram username without the @ symbol')
                                ->columnSpanFull(),
                            TextInput::make('youtube')
                                ->label('YouTube Channel')
                                ->url()
                                ->placeholder('your_youtube_channel')
                                ->prefix('youtube.com/')
                                ->helperText('Enter your YouTube channel name or ID')
                                ->columnSpanFull(),
                                TagsInput::make('tags')
                                    ->label('Tags - Skills & Expertise')
                                    ->placeholder('Add tags to your profile')
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            // $this->getCancelFormAction(), // Remove or comment this line
        ];
    }
}
