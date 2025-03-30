<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('user can delete their account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post(route('settings.profile.destroy'), [
        'password' => 'password',
    ])->assertValid()->assertRedirect('/');

    $this->assertGuest();
    $this->assertModelMissing($user);
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post(route('settings.profile.destroy'), [
        'password' => 'wrong-password',
    ])->assertInvalid(['password']);

    $this->assertModelExists($user);
});
