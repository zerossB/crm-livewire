<?php

use App\Livewire\Admin\Users;
use App\Models\User;

use function Pest\Laravel\{actingAs, assertNotSoftDeleted, assertSoftDeleted};

test('should be able to delete a user', function () {
    $user = User::factory()->admin()->create();

    actingAs($user);

    $userDeleted = User::factory()->create();

    $component = \Livewire\Livewire::test(Users\Delete::class, [
        'user' => $userDeleted,
    ]);

    $component->set('confirmation_confirmation', 'DART VADER')
        ->call('destroy')
        ->assertDispatched('user:deleted');

    assertSoftDeleted('users', [
        'id' => $userDeleted->id,
    ]);
});

it('should have a confirmation before deletion', function () {
    $user = User::factory()->admin()->create();

    actingAs($user);

    $userDeleted = User::factory()->create();

    $component = \Livewire\Livewire::test(Users\Delete::class, [
        'user' => $userDeleted,
    ]);

    $component->call('destroy')
        ->assertHasErrors([
            'confirmation' => 'confirmed',
        ])
        ->assertNotDispatched('user:deleted');

    assertNotSoftDeleted('users', [
        'id' => $userDeleted->id,
    ]);
});
