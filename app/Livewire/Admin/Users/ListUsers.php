<?php

namespace App\Livewire\Admin\Users;

use App\Enums\Can;
use Illuminate\View\View;
use Livewire\Component;

class ListUsers extends Component
{
    public function mount(): void
    {
        $this->authorize(Can::BE_AN_ADMIN);
    }

    public function render(): View
    {
        return view('livewire.admin.users.list-users');
    }
}
