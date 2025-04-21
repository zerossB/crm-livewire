<?php

use App\Livewire\Admin\Dashboard;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, get};

it('should be block the access to the page if the user does not have the permission to view the dashboard', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(Dashboard::class)
        ->assertForbidden();

    get(route('admin.dashboard'))
        ->assertForbidden();
});
