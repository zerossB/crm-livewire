<?php

namespace App\Livewire\Auth;

use App\Models\User;
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
        return view('livewire.auth.register');
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'max:255', 'email', 'confirmed'],
            'password' => ['required', 'max:255'],
        ];
    }

    public function register(): void
    {
        $this->validate();

        User::query()->create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);
    }
}
