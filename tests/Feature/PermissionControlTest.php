<?php

use App\Models\{Permission, User};
use Database\Seeders\{PermissionSeeder, UserSeeder};

use function Pest\Laravel\assertDatabaseHas;

test('should be able to give an user a permission to do something', function () {
    $user = User::factory()->create();

    $user->givePermissionTo('be an admin');

    expect($user)
        ->hasPermissionTo('be an admin')
        ->toBeTrue();

    assertDatabaseHas('permissions', [
        'name' => 'be an admin',
    ]);

    assertDatabaseHas('permission_user', [
        'user_id'       => $user->id,
        'permission_id' => Permission::query()->whereName('be an admin')->first()->id,
    ]);
});

test('permission has to have a seeder', function () {
    $this->seed([
        PermissionSeeder::class,
        UserSeeder::class,
    ]);

    assertDatabaseHas('permissions', [
        'name' => 'be an admin',
    ]);

    assertDatabaseHas('permission_user', [
        'user_id'       => User::first()->id,
        'permission_id' => Permission::query()->whereName('be an admin')->first()->id,
    ]);
});

it('should block the access to an admin page if the user does not have the permission to be an admin', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.home'))
        ->assertForbidden();
});

test('let`s make sure that we are using cache to store user permission', function () {
    $user = User::factory()->create();

    $user->givePermissionTo('be an admin');

    $cacheKey = "User::{$user->id}::permissions";

    expect(Cache::has($cacheKey))
        ->toBeTrue('Checking if the cache has the key')
        ->and(Cache::get($cacheKey))
        ->toBe($user->permissions, 'Checking if the cache has the permissions');
});

it('let`s make shure that we are using the cache the retrieve/check when the user has the given permission', function () {
    $user = User::factory()->create();

    $user->givePermissionTo('be an admin');

    \Illuminate\Support\Facades\DB::listen(function ($query) {
        if (str_contains($query->sql, 'permissions')) {
            expect($query->bindings)->toBeEmpty();
        }
    });

    $user->hasPermissionTo('be an admin');

    expect(true)->toBeTrue();
});
