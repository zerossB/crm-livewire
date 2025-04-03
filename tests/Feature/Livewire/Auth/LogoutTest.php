<?php

use App\Livewire\Auth\Logout;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('renders successfully', function () {
    Livewire::test(Logout::class)
        ->assertOk();
});

test('should be logout user', function () {
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(Logout::class)
        ->call('logout')
        ->assertRedirect(route('login'));

    expect(auth()->check())
        ->toBeFalse();
});
