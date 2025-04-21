<?php

use App\Enums\Can;
use App\Models\{Permission, User};
use Database\Seeders\{PermissionSeeder, UserSeeder};

use function Pest\Laravel\assertDatabaseHas;

test('should be able to give an user a permission to do something', function () {
    $user = User::factory()->create();

    $user->givePermissionTo(Can::BE_AN_ADMIN);

    expect($user)
        ->hasPermissionTo(Can::BE_AN_ADMIN)
        ->toBeTrue();

    assertDatabaseHas('permissions', [
        'name' => Can::BE_AN_ADMIN->value,
    ]);

    assertDatabaseHas('permission_user', [
        'user_id'       => $user->id,
        'permission_id' => Permission::query()->whereName(Can::BE_AN_ADMIN->value)->first()->id,
    ]);
});

test('permission has to have a seeder', function () {
    $this->seed([
        PermissionSeeder::class,
        UserSeeder::class,
    ]);

    assertDatabaseHas('permissions', [
        'name' => Can::BE_AN_ADMIN->value,
    ]);

    assertDatabaseHas('permission_user', [
        'user_id'       => User::first()->id,
        'permission_id' => Permission::query()->whereName(Can::BE_AN_ADMIN->value)->first()->id,
    ]);
});

it('should block the access to an admin page if the user does not have the permission to\App\Enums\Can::BE_AN_ADMIN->value', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.home'))
        ->assertForbidden();
});

test('let`s make sure that we are using cache to store user permission', function () {
    $user = User::factory()->create();

    $user->givePermissionTo(Can::BE_AN_ADMIN);

    $cacheKey = "User::{$user->id}::permissions";

    expect(Cache::has($cacheKey))
        ->toBeTrue('Checking if the cache has the key')
        ->and(Cache::get($cacheKey))
        ->toBe($user->permissions, 'Checking if the cache has the permissions');
});

it('let`s make shure that we are using the cache the retrieve/check when the user has the given permission', function () {
    $user = User::factory()->create();

    $user->givePermissionTo(Can::BE_AN_ADMIN);

    \Illuminate\Support\Facades\DB::listen(function ($query) {
        if (str_contains($query->sql, 'permissions')) {
            expect($query->bindings)->toBeEmpty();
        }
    });

    $user->hasPermissionTo(Can::BE_AN_ADMIN);

    expect(true)->toBeTrue();
});
