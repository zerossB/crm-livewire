<?php

use App\Livewire\Auth\Register;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Livewire\Livewire;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

it('renders successfully', function () {
    Livewire::test(Register::class)
        ->assertOk();
});

it('should be able to register a new user in the system', function () {
    Livewire::test(Register::class)
        ->set('name', 'John Doe')
        ->set('email', 'john@doe.com')
        ->set('email_confirmation', 'john@doe.com')
        ->set('password', 'password')
        ->call('register')
        ->assertHasNoErrors()
        ->assertRedirect(route('home'));

    assertDatabaseHas('users', [
        'name' => 'John Doe',
        'email' => 'john@doe.com',
    ]);

    assertDatabaseCount('users', 1);

    expect(auth()->check())
        ->and(auth()->user())
        ->toBeInstanceOf(User::class)
        ->email
        ->toBe('john@doe.com');
});

it('validation rules', function ($testCase) {
    if ($testCase->rule === 'unique') {
        User::factory()->create([
            $testCase->field => $testCase->value,
        ]);
    }

    Livewire::test(Register::class)
        ->set($testCase->field, $testCase->value)
        ->call('register')
        ->assertHasErrors([
            $testCase->field => $testCase->rule,
        ]);
})
    ->with([
        'name::required' => (object)['field' => 'name', 'value' => '', 'rule' => 'required'],
        'name::max:255' => (object)['field' => 'name', 'value' => str_repeat('*', 256), 'rule' => 'max'],
        'email::required' => (object)['field' => 'email', 'value' => '', 'rule' => 'required'],
        'email::email' => (object)['field' => 'email', 'value' => 'not-an-email', 'rule' => 'email'],
        'email::max:255' => (object)['field' => 'email', 'value' => str_repeat('*', 256) . '@dow.com', 'rule' => 'max'],
        'email::confirmed' => (object)['field' => 'email', 'value' => 'john@doe.com', 'rule' => 'confirmed'],
        'email::unique' => (object)['field' => 'email', 'value' => 'john@doe.com', 'rule' => 'unique'],
        'password::required' => (object)['field' => 'password', 'value' => '', 'rule' => 'required'],
        'password::max:255' => (object)['field' => 'password', 'value' => str_repeat('*', 256), 'rule' => 'max'],
    ]);

it('should send a notification welcoming the new user', function () {
    Notification::fake();

    Livewire::test(Register::class)
        ->set('name', 'John Doe')
        ->set('email', 'john@doe.com')
        ->set('email_confirmation', 'john@doe.com')
        ->set('password', 'password')
        ->call('register');

    Notification::assertSentTo(auth()->user(), \App\Notifications\Auth\WelcomeNewUser::class);
});
