<?php

use App\Livewire\Auth\PasswordRecovery;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(PasswordRecovery::class)
        ->assertStatus(200);
});

it('needs to have a route to recovery password', function () {
    $this->get(route('password.recovery'))
        ->assertStatus(200);
});

it('should be able to request for a password recovery', function () {
    $user = \App\Models\User::factory()->create();

    Livewire::test(PasswordRecovery::class)
        ->set('email', $user->email)
        ->call('request')
        ->assertHasNoErrors()
        ->assertSee(__('We have sent you a password recovery link'));
});
