<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Livewire\Component;

class PasswordRecovery extends Component
{
    public string $email = '';

    public string $successMessage = '';

    public function render(): View
    {
        return view('livewire.auth.password-recovery')
            ->layout('components.layouts.guest');
    }

    protected function rules(): array
    {
        return [
            'email' => ['required', 'email'],
        ];
    }

    public function recoveryPassword(): void
    {
        $this->validate();

        $status = Password::sendResetLink(['email' => $this->email]);

        $this->reset([
            'email',
        ]);

        $this->successMessage = __($status);
    }
}
