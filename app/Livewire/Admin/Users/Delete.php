<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Notifications\UserDeletedNotification;
use Illuminate\View\View;
use Livewire\Component;

class Delete extends Component
{
    public User $user;

    public string $confirmation = 'DART VADER';

    public ?string $confirmation_confirmation = null;

    public bool $modal = false;

    protected function rules(): array
    {
        return [
            'confirmation' => ['required', 'confirmed'],
        ];
    }

    public function render(): View
    {
        return view('livewire.admin.users.delete');
    }

    public function destroy(): void
    {
        $this->validate();

        $this->user->delete();

        $this->user->notify(new UserDeletedNotification());

        $this->dispatch('user:deleted');
    }
}
