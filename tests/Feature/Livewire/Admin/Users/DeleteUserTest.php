<?php

use App\Livewire\Admin\Users;
use App\Models\User;

use function Pest\Laravel\{actingAs, assertSoftDeleted};

test('should be able to delete a user', function () {
    $user = User::factory()->admin()->create();

    actingAs($user);

    $userDeleted = User::factory()->create();

    $component = \Livewire\Livewire::test(Users\Delete::class, [
        'user' => $userDeleted,
    ]);

    $component->call('destroy')
        ->assertDispatched('user:deleted');

    assertSoftDeleted('users', [
        'id' => $userDeleted->id,
    ]);
});
