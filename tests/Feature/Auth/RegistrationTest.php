<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('registration screen can be rendered', function () {
    $this->get('/register')->assertOk();
});

test('new users can register', function () {
    $this->post(route('register'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertValid()->assertRedirect();

    $this->assertAuthenticated();
});

test('new users get a personal team', function () {
    $this->post(route('register'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertValid();

    $user = User::firstWhere('email', 'test@example.com');

    expect($user->personalTeam())->not->toBeNull();
    expect($user->personalTeam()->is_personal)->toBeTrue();
    expect($user->current_team_id)->toBe($user->personalTeam()->id);
});
