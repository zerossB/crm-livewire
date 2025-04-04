<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\{Hash, Password as PasswordFacade};
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class PasswordReset extends Component
{
    #[Locked]
    public string $token = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(string $token = ''): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    public function render(): View
    {
        return view('livewire.auth.password-reset')
            ->layout('components.layouts.guest');
    }

    public function rules(): array
    {
        return [
            'token'    => ['required'],
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ];
    }

    public function resetPassword(): void
    {
        $this->validate();

        $status = PasswordFacade::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password'       => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new \Illuminate\Auth\Events\PasswordReset($user));
            }
        );

        if ($status != PasswordFacade::PasswordReset) {
            $this->addError('email', __($status));

            return;
        }

        session()->flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}
