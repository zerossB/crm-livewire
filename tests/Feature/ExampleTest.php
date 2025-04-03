<?php

it('the application returns a successful response', function () {
    \Pest\Laravel\actingAs(\App\Models\User::factory()->create());

    $response = $this->get('/');

    $response->assertStatus(200);
});

it('the application returns a 404 response', function () {
    $response = $this->get('/non-existing-route');

    $response->assertStatus(404);
});

it('dashboard only accessible to authenticated users', function () {
    $response = $this->get('/');

    $response->assertRedirect('/login');
});
