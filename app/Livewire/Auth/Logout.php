<?php

namespace App\Livewire\Auth;

use Illuminate\View\View;
use Livewire\Component;

class Logout extends Component
{
    public function render(): View
    {
        return view('livewire.auth.logout');
    }

    public function logout(): void
    {
        auth()->logout();

        session()->invalidate();
        session()->regenerateToken();

        redirect(route('login'));
    }
}
