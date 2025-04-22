<?php

namespace App\Livewire\Admin\Users;

use App\Enums\Can;
use App\Models\{Permission, User};
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\{Component, WithPagination};

/**
 * @property array|LengthAwarePaginator $users
 * @property array $headers
 * @property array $permissions
 */
class ListUsers extends Component
{
    use WithPagination;

    public string $search = '';

    public array $searchPermissions = [];

    public bool $drawer = false;

    public function mount(): void
    {
        $this->authorize(Can::BE_AN_ADMIN);
    }

    public function render(): View
    {
        return view('livewire.admin.users.list-users');
    }

    public function clear(): void
    {
        $this->reset([
            'search',
            'searchPermissions',
        ]);
    }

    #[Computed]
    public function users(): array|LengthAwarePaginator
    {
        $this->validate([
            'search'              => 'nullable|string|max:255',
            'searchPermissions'   => 'array',
            'searchPermissions.*' => 'exists:permissions,id',
        ]);

        $search = str($this->search)->lower();

        return User::with('permissions')
            ->when($this->search, fn ($query) => $query->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(email) LIKE ?', ["%{$search}%"]))
            ->when($this->searchPermissions, function ($query) {
                $query->whereHas('permissions', function ($query) {
                    $query->whereIn('id', $this->searchPermissions);
                });
            })
            ->paginate();
    }

    #[Computed]
    public function permissions(): array
    {
        return Permission::query()
            ->orderBy('name')
            ->get()->map(fn ($permission) => [
                'id'   => $permission->id,
                'name' => $permission->name,
            ])
            ->toArray();
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
