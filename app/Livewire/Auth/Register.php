<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Notifications\Auth\WelcomeNewUser;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Livewire\Component;

class Register extends Component
{
    public ?string $name = null;
    public ?string $email = null;
    public ?string $email_confirmation = null;
    public ?string $password = null;

    public function render(): View
    {
        return view('livewire.auth.register')
            ->layout('components.layouts.guest');
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'max:255', 'email', 'unique:users,email', 'confirmed'],
            'password' => ['required', 'max:255', Password::defaults()],
        ];
    }

    public function register(): void
    {
        $this->validate();

        $user = User::query()->create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);

        auth()->login($user);

        $user->notify(new WelcomeNewUser());

        $this->redirect(route('home'));
    }
}
