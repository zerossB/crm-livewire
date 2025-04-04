<?php

use App\Livewire\Auth\PasswordRecovery;
use Illuminate\Auth\Events\PasswordResetLinkSent;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(PasswordRecovery::class)
        ->assertStatus(200);
});

it('needs to have a route to recovery password', function () {
    $this->get(route('password.recovery'))
        ->assertSeeLivewire('auth.password-recovery')
        ->assertStatus(200);
});

it('should be able to request for a password recovery', function () {
    $user = \App\Models\User::factory()->create();

    Notification::fake();

    Livewire::test(PasswordRecovery::class)
        ->set('email', $user->email)
        ->call('recoveryPassword')
        ->assertHasNoErrors()
        ->assertSee(__('We have emailed your password reset link.'));

    Notification::assertNotSentTo($user, PasswordResetLinkSent::class);
});

it('should not be able to request for a password recovery with an invalid email', function ($value, $rule) {
    Livewire::test(PasswordRecovery::class)
        ->set('email', $value)
        ->call('recoveryPassword')
        ->assertHasErrors(['email' => $rule]);
})->with([
    'required' => ['value' => '', 'rule' => 'required'],
    'email'    => ['value' => 'invalid-email', 'rule' => 'email'],
]);
