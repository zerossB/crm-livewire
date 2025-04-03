<?php

use App\Livewire\Auth\Login;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Login::class)
        ->assertOk();
});

it('should be able to login', function () {
    \App\Models\User::factory()->create([
        'email' => 'john@doe.com',
    ]);

    Livewire::test(Login::class)
        ->set('email', 'john@doe.com')
        ->set('password', 'password')
        ->call('login')
        ->assertHasNoErrors()
        ->assertRedirect(route('home'));

    expect(Auth::check())->toBeTrue()
        ->and(Auth::user()->email)->toBe('john@doe.com');
});

it('should make shure to inform the user an error when email and password dosent work', function () {
    Livewire::test(Login::class)
        ->set('email', 'john@doe.com')
        ->set('password', 'password')
        ->call('login')
        ->assertHasErrors([
            'invalidCredentials',
        ])
        ->assertSee(__('auth.failed'));
});
