<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;

class Register extends Component
{
    public ?string $name;
    public ?string $email;
    public ?string $email_confirmation;
    public ?string $password;

    public function render(): View
    {
        return view('livewire.auth.register');
    }

    public function register(): void
    {
        User::query()->create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);
    }
}
