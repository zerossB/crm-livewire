<?php

use App\Models\{Permission, User};

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
