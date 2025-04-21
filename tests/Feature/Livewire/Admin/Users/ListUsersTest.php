<?php

use App\Livewire\Admin\Users\ListUsers;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('renders successfully', function () {
    $user = User::factory()
        ->admin()
        ->create();

    actingAs($user);

    Livewire::test(ListUsers::class)
        ->assertStatus(200);
});

it('can only be accessed by an admin', function () {
    $user = User::factory()
        ->create();

    Livewire::actingAs($user)
        ->test(ListUsers::class)
        ->assertForbidden();
});
