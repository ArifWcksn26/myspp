<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;

class Biodata extends Page
{
  
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.biodata';

    public $user;

    public array $data = [];

    public function mount(): void {
    $this->user = Auth::user();

    // inisialisasi form dengan current data
    $this->form->fill([
        'name' => $this->user->name,
        'email' => $this->user->email,
        'phone' => $this->user->phone,
        'image' => $this->user->image,
        'scancard' => $this->user->scancard,
    ]);
    }

    public function form(Form $form): Form
{
    return $form->schema([
        Section::make([
            TextInput::make('name')->required(),
            TextInput::make('email')->required(),
            TextInput::make('password')
                ->password()
                ->revealable(filament()-> arePasswordsRevealable())
                ->nullable(),
            TextInput::make('phone')->required(),
            FileUpload::make('image')->image()->columnSpanFull(),
            FileUpload::make('scancard')->image()->columnSpanFull(),
        ]),
    ])->statePath('data');
}

    public function edit(): void {
        // Validate form data
        $validatedData = $this->form->getState();

        // update the user's details
        $this->user->name = $validatedData['name'];
        $this->user->email = $validatedData['email'];
        $this->user->phone = $validatedData['phone'];

        // update password if provided
        if (!empty($validatedData['password'])) {
            $this->user->password = Hash::make($validatedData['password']);
        }

        // Handle image upload
        if (isset($validatedData['image'])) {
            if ($this->user->image) {
                Storage::delete($this->user->image);
            }
            $this->user->image = $validatedData['image'];
        }

        // Handle Scancard upload
        if (isset($validatedData['scancard'])) {
            if ($this->user->scancard) {
                Storage::delete($this->user->scancard);
            }
            $this->user->scancard = $validatedData['scancard'];
        }

        $this->user->save();

        // Send a success notification
        Notification::make()
            ->title('Biodata Updated')
            ->success()
            ->body('Your biodata has been successfully updated.')
            ->send();

    }
}
