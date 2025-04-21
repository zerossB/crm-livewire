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

it('let`s create a livewire component to list users in the page', function () {
    $user = User::factory()->admin()->create();

    User::factory()
        ->count(10)
        ->create();

    actingAs($user);

    $component = Livewire::test(ListUsers::class);

    expect($component->instance()->users)->toBeInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class)
        ->and($component->instance()->users->count())->toBe(11);

    User::all()->each(function ($user) use ($component) {
        $component->assertSee($user->name);
    });
});

it('check the table headers', function () {
    $user = User::factory()->admin()->create();
    actingAs($user);

    $component = Livewire::test(ListUsers::class);

    expect($component->instance()->headers)->toBe([
        ['key' => 'id', 'label' => '#'],
        ['key' => 'name', 'label' => 'Name'],
        ['key' => 'email', 'label' => 'Email'],
        ['key' => 'permissions', 'label' => 'Permissions'],
    ]);

    $component->assertSee('Name')
        ->assertSee('Email');
});
