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

it('should be able to search for a user', function () {
    $user = User::factory()->admin()->create();
    actingAs($user);

    $users = User::factory()
        ->count(10)
        ->create();

    $randomName = $users->random()->name;

    $component = Livewire::test(ListUsers::class);

    $component->set('search', $randomName)
        ->assertSet('search', function ($searchText) use ($randomName) {
            expect($searchText)
                ->toBe($randomName)
                ->and(str($searchText)->contains($randomName))->toBeTrue();

            return true;
        })
        ->assertSee($randomName)
        ->assertDontSee($user->name);

    expect($component->instance()->users)
        ->toHaveCount(1)
        ->first()
        ->name->toBe($randomName);
});

it('should be able to filter by permissions', function () {
    $user = User::factory()->admin()->create();
    actingAs($user);

    $users = User::factory()
        ->count(10)
        ->create();

    $randomUser = $users->random();

    $component = Livewire::test(ListUsers::class);

    $permission = \App\Models\Permission::whereName(\App\Enums\Can::BE_AN_ADMIN->value)->first();

    $component->set('searchPermissions', [$permission->id])
        ->assertSet('searchPermissions', function ($permissions) {
            expect($permissions)
                ->toBe([1]);

            return true;
        })
        ->assertCount('users', 1)
        ->assertDontSee($randomUser->name)
        ->assertSee($user->name);
});

it('should be able to filter trashed users', function () {
    $user = User::factory()->admin()->create();
    actingAs($user);

    $users = User::factory()
        ->count(10)
        ->create();

    $randomUser = $users->random();

    $component = Livewire::test(ListUsers::class);

    $component->set('searchTrash', false)
        ->assertSet('searchTrash', false)
        ->assertCount('users', 11)
        ->assertSee($user->name);

    $randomUser->delete();

    $component->set('searchTrash', true)
        ->assertSet('searchTrash', true)
        ->assertCount('users', 1)
        ->assertSee($randomUser->name);
});

it('should be able to clear the search', function () {
    $user = User::factory()->admin()->create();
    actingAs($user);

    $users = User::factory()
        ->count(10)
        ->create();

    $randomName = $users->random()->name;

    $component = Livewire::test(ListUsers::class);

    $component->set('search', $randomName)
        ->assertSet('search', function ($searchText) use ($randomName) {
            expect($searchText)
                ->toBe($randomName)
                ->and(str($searchText)->contains($randomName))->toBeTrue();

            return true;
        })
        ->assertSee($randomName)
        ->assertDontSee($user->name);

    expect($component->instance()->users)
        ->toHaveCount(1)
        ->first()
        ->name->toBe($randomName);

    $component->call('clear')
        ->assertSet('search', '')
        ->assertCount('users', 11);
});

it('should be able to clear the permissions', function () {
    $user = User::factory()->admin()->create();
    actingAs($user);

    $users = User::factory()
        ->count(10)
        ->create();

    $randomUser = $users->random();

    $component = Livewire::test(ListUsers::class);

    $permission = \App\Models\Permission::whereName(\App\Enums\Can::BE_AN_ADMIN->value)->first();

    $component->set('searchPermissions', [$permission->id])
        ->assertSet('searchPermissions', function ($permissions) {
            expect($permissions)
                ->toBe([1]);

            return true;
        })
        ->assertCount('users', 1)
        ->assertDontSee($randomUser->name)
        ->assertSee($user->name);

    $component->call('clear')
        ->assertSet('searchPermissions', [])
        ->assertCount('users', 11);
});

it('should be able to open the drawer', function () {
    $user = User::factory()->admin()->create();
    actingAs($user);

    $users = User::factory()
        ->count(10)
        ->create();

    $component = Livewire::test(ListUsers::class);

    $component->set('drawer', true)
        ->assertSet('drawer', true);
});

it('should be able to close the drawer', function () {
    $user = User::factory()->admin()->create();
    actingAs($user);

    $users = User::factory()
        ->count(10)
        ->create();

    $component = Livewire::test(ListUsers::class);

    $component->set('drawer', true)
        ->assertSet('drawer', true)
        ->set('drawer', false)
        ->assertSet('drawer', false);
});

it('should be able to order name column asc', function () {
    $user = User::factory()->admin()->create();
    actingAs($user);

    $users = User::factory()
        ->count(10)
        ->create();

    $firstUser = $users->sortBy('name')->first();

    $component = Livewire::test(ListUsers::class);

    $component->set('sortBy', [
        'column'    => 'name',
        'direction' => 'asc',
    ])
        ->assertSet('sortBy', [
            'column'    => 'name',
            'direction' => 'asc',
        ])
        ->assertSet('users', function ($users) use ($firstUser) {
            expect($users)
                ->toHaveCount(11)
                ->first()
                ->name->toBe($firstUser->name);

            return true;
        })
        ->assertSee($firstUser->name);
});

it('should be able to order name column desc', function () {
    $user = User::factory()->admin()->create();
    actingAs($user);

    $users = User::factory()
        ->count(10)
        ->create();

    $firstUser = $users->sortBy('name', descending: true)->first();

    $component = Livewire::test(ListUsers::class);

    $component->set('sortBy', [
        'column'    => 'name',
        'direction' => 'desc',
    ])
        ->assertSet('sortBy', [
            'column'    => 'name',
            'direction' => 'desc',
        ])
        ->assertSet('users', function ($users) use ($firstUser) {
            expect($users)
                ->toHaveCount(11)
                ->first()
                ->name->toBe($firstUser->name);

            return true;
        })
        ->assertSee($firstUser->name);
});
