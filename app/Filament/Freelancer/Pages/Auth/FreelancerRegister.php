<?php

namespace App\Filament\Freelancer\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Validation\Rules\Password;

class FreelancerRegister extends BaseRegister
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(), // Default name field
                $this->getEmailFormComponent(), // Default email field
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->rule(Password::min(8) // Minimum 8 characters
                        ->mixedCase() // Must have uppercase and lowercase
                        ->numbers() // Must include numbers
                        ->symbols() // Must include special characters
                        ->uncompromised()), // Not found in data breaches
                $this->getPasswordConfirmationFormComponent(), // Default confirmation field
            ]);
    }
}
