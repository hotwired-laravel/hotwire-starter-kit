<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('profile page is displayed', function () {
    $this->actingAs(User::factory()->create());

    $this->get(route('settings.profile.edit'))->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->put(route('settings.profile.update'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ])->assertValid();

    $user->refresh();

    expect($user->name)->toEqual('Test User');
    expect($user->email)->toEqual('test@example.com');
    expect($user->email_verified_at)->toBeNull();
});

test('email verification status is unchanged when email address is unchanged', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->put(route('settings.profile.update'), [
        'name' => 'Test User',
        'email' => $user->email,
    ])->assertValid();

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});
