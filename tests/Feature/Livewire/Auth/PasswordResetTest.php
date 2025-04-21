<?php

use App\Livewire\Auth\{PasswordRecovery, PasswordReset};
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Livewire\Livewire;

use function Pest\Laravel\get;

it('reset password link screen can be rendered', function () {
    get(route('password.reset', [
        'token' => 'test-token',
    ]))
        ->assertOk();
});

it('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(PasswordRecovery::class)
        ->set('email', $user->email)
        ->call('recoveryPassword');

    Notification::assertSentTo($user, ResetPassword::class);
});

it('reset password screen can be rendered', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(PasswordRecovery::class)
        ->set('email', $user->email)
        ->call('recoveryPassword');

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
        get(route('password.reset', [
            'token' => $notification->token,
        ]))
            ->assertOk();

        return true;
    });
});

it('password can be reset with valid token', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(PasswordRecovery::class)
        ->set('email', $user->email)
        ->call('recoveryPassword');

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        $response = Livewire::test(PasswordReset::class, ['token' => $notification->token])
            ->set('email', $user->email)
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('resetPassword');

        $response
            ->assertHasNoErrors()
            ->assertRedirect(route('login', absolute: false));

        return true;
    });
});
