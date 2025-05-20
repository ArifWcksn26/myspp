<?php

namespace App\Filament\Auth;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;

use Filament\Pages\Auth\Register as AuthRegister;

class Register extends AuthRegister 
{

    protected function getForms(): array
    {
    return [
        'form' => $this->form(
            $this->makeform()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->label('Phone Number')
                            ->placeholder('Enter your phone number'),
                        FileUpload::make('image')
                            ->label('Profile Picture')
                            ->columnSpanFull()
                            ->required()
                            ->image()
                            ->placeholder('Upload your profile picture'),
                        FileUpload::make('Scan Card')
                            ->label('Scan Of Card')
                            ->columnSpanFull()
                            ->required()
                            ->image()
                            ->placeholder('Upload your card'),
                    ])
                    ->statePath('data'),
                )
        ];
    }

    protected function submit(): void {
        $data = $this->form->getState();
    
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'image' => $data['image'] ?? null,
            'scancard' => $data['scancard'] ?? null,
        ]);
    
        Auth::login($user);
    }
}