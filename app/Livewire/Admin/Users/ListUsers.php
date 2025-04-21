<?php

namespace App\Livewire\Admin\Users;

use App\Enums\Can;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\{Component, WithPagination};

/**
 * @property array|LengthAwarePaginator $users
 * @property array $headers
 */
class ListUsers extends Component
{
    use WithPagination;

    public bool $drawer = false;

    public function mount(): void
    {
        $this->authorize(Can::BE_AN_ADMIN);
    }

    public function render(): View
    {
        return view('livewire.admin.users.list-users');
    }

    #[Computed]
    public function users(): array|LengthAwarePaginator
    {
        return User::with('permissions')->paginate();
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'permissions', 'label' => 'Permissions'],
        ];
    }
}
