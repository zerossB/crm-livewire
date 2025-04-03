<?php

use App\Livewire\Auth\Register;
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
        ->assertHasNoErrors();

    assertDatabaseHas('users', [
        'name' => 'John Doe',
        'email' => 'john@doe.com',
    ]);

    assertDatabaseCount('users', 1);
});

it('validation rules', function ($testCase) {
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
        'password::required' => (object)['field' => 'password', 'value' => '', 'rule' => 'required'],
        'password::max:255' => (object)['field' => 'password', 'value' => str_repeat('*', 256), 'rule' => 'max'],
    ]);
