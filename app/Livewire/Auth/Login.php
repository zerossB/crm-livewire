<?php

namespace App\Livewire\Auth;

use Illuminate\View\View;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public function render(): View
    {
        return view('livewire.auth.login');
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
        $this->validate();

        if (auth()->attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->redirect(route('home'));
        }

        session()->flash('error', 'Invalid credentials.');
    }
}
