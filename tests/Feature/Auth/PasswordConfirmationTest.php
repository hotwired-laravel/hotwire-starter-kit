<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('confirm password screen can be rendered', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/confirm-password')
        ->assertOk();
});

test('password can be confirmed', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post(route('password.confirm.store'), [
        'password' => 'password',
    ])->assertValid()->assertRedirect(route('dashboard'));
});

test('password is not confirmed with invalid password', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post(route('password.confirm.store'), [
        'password' => 'wrong-password',
    ])->assertInvalid(['password']);
});
