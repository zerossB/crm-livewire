<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public function render(): View
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.guest');
    }

    protected function rules(): array
    {
        return [
            'email'    => 'required|email|max:255',
            'password' => 'required|string|max:255',
        ];
    }

    public function login(): void
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            $seconds = RateLimiter::availableIn($this->throttleKey());

            $this->addError('throttle', __('auth.throttle', ['seconds' => $seconds]));

            return;
        }

        $this->validate();

        if (auth()->attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->redirect(route('home'));

            return;
        }

        RateLimiter::hit($this->throttleKey(), 5);
        $this->addError('invalidCredentials', __('auth.failed'));
    }

    private function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower($this->email) . '|' . request()->ip()
        );
    }
}
