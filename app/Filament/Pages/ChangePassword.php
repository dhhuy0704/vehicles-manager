<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ChangePassword extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationGroup = 'Account Settings';
    protected static ?string $navigationLabel = 'Change Password';
    protected static ?string $title = 'Change Password';
    protected static ?string $slug = 'change-password';

    protected static string $view = 'filament.pages.change-password';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('current_password')
                            ->password()
                            ->required()
                            ->label('Current Password')
                            ->rules(['required', 'string']),
                        TextInput::make('new_password')
                            ->password()
                            ->required()
                            ->label('New Password')
                            ->rules(['required', 'string', 'min:8']),
                        TextInput::make('new_password_confirmation')
                            ->password()
                            ->required()
                            ->label('Confirm New Password')
                            ->rules(['required', 'string', 'same:data.new_password']),
                    ])
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        $user = Auth::user();

        // Verify current password
        if (! Hash::check($data['current_password'], $user->password)) {
            Notification::make()
                ->title('Current password is incorrect')
                ->danger()
                ->send();

            return;
        }

        // Update password
        DB::table('users')
            ->where('id', Auth::id())
            ->update(['password' => Hash::make($data['new_password'])]);

        // Clear form
        $this->form->fill();

        Notification::make()
            ->title('Password changed successfully')
            ->success()
            ->send();
    }
}