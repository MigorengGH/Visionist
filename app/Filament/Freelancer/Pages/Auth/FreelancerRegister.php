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
                //$this->getPhoneFormComponent(), // Default phone field
                $this->getPasswordFormComponent(),

                $this->getPasswordConfirmationFormComponent(), // Default confirmation field
            ]);
    }
}
