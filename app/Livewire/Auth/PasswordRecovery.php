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
        return view('livewire.auth.password-recovery');
    }

    protected function rules(): array
    {
        return [
            'email' => ['required', 'email'],
        ];
    }

    public function request(): void
    {
        $this->validate();

        Password::sendResetLink(['email' => $this->email]);

        $this->successMessage = __('We have sent you a password recovery link');
    }
}
