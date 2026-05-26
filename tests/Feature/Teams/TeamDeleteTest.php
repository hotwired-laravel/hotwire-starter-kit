<?php

use App\Enums\TeamRole;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('owner can delete a non-personal team', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($user, ['role' => TeamRole::Owner->value]);

    $this->actingAs($user)
        ->post(route('settings.teams.destroy', $team), ['confirmation' => 'delete'])
        ->assertValid()
        ->assertRedirect(route('settings.teams.index'))
        ->assertSessionHas('notice', 'Team deleted.');

    $this->assertSoftDeleted($team);
});

test('owner sees a validation error when confirmation does not match', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($user, ['role' => TeamRole::Owner->value]);

    $this->actingAs($user)
        ->post(route('settings.teams.destroy', $team), ['confirmation' => 'nope'])
        ->assertInvalid(['confirmation']);

    expect($team->fresh()->trashed())->toBeFalse();
});

test('owner sees a validation error when confirmation is missing', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($user, ['role' => TeamRole::Owner->value]);

    $this->actingAs($user)
        ->post(route('settings.teams.destroy', $team), [])
        ->assertInvalid(['confirmation']);

    expect($team->fresh()->trashed())->toBeFalse();
});

test('admins cannot delete a team', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($user, ['role' => TeamRole::Admin->value]);

    $this->actingAs($user)
        ->get(route('settings.teams.delete', $team))
        ->assertForbidden();

    $this->actingAs($user)
        ->post(route('settings.teams.destroy', $team), ['confirmation' => 'delete'])
        ->assertForbidden();

    expect($team->fresh()->trashed())->toBeFalse();
});

test('members cannot delete a team', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($user, ['role' => TeamRole::Member->value]);

    $this->actingAs($user)
        ->get(route('settings.teams.delete', $team))
        ->assertForbidden();

    $this->actingAs($user)
        ->post(route('settings.teams.destroy', $team), ['confirmation' => 'delete'])
        ->assertForbidden();

    expect($team->fresh()->trashed())->toBeFalse();
});

test('non-members cannot delete a team', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create();

    $this->actingAs($user)
        ->get(route('settings.teams.delete', $team))
        ->assertForbidden();

    $this->actingAs($user)
        ->post(route('settings.teams.destroy', $team), ['confirmation' => 'delete'])
        ->assertForbidden();

    expect($team->fresh()->trashed())->toBeFalse();
});

test('personal teams cannot be deleted by their owner', function () {
    $user = User::factory()->create();
    $team = Team::factory()->personal()->create();
    $team->members()->attach($user, ['role' => TeamRole::Owner->value]);

    $this->actingAs($user)
        ->get(route('settings.teams.delete', $team))
        ->assertForbidden();

    $this->actingAs($user)
        ->post(route('settings.teams.destroy', $team), ['confirmation' => 'delete'])
        ->assertForbidden();

    expect($team->fresh()->trashed())->toBeFalse();
});

test('deleting the current team switches the user to a fallback team', function () {
    $user = User::factory()->create();
    $fallback = $user->personalTeam();

    $team = Team::factory()->create(['name' => 'Doomed']);
    $team->members()->attach($user, ['role' => TeamRole::Owner->value]);
    $user->switchTeam($team);

    expect($user->fresh()->current_team_id)->toBe($team->id);

    $this->actingAs($user)
        ->post(route('settings.teams.destroy', $team), ['confirmation' => 'delete'])
        ->assertRedirect(route('settings.teams.index'));

    expect($user->fresh()->current_team_id)->toBe($fallback->id);
    $this->assertSoftDeleted($team);
});

test('deleting a non-current team leaves the user current team untouched', function () {
    $user = User::factory()->create();
    $personal = $user->personalTeam();

    $team = Team::factory()->create();
    $team->members()->attach($user, ['role' => TeamRole::Owner->value]);

    expect($user->fresh()->current_team_id)->toBe($personal->id);

    $this->actingAs($user)
        ->post(route('settings.teams.destroy', $team), ['confirmation' => 'delete'])
        ->assertRedirect(route('settings.teams.index'));

    expect($user->fresh()->current_team_id)->toBe($personal->id);
    $this->assertSoftDeleted($team);
});

test('the delete confirmation page is rendered for owners', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($user, ['role' => TeamRole::Owner->value]);

    $this->actingAs($user)
        ->get(route('settings.teams.delete', $team))
        ->assertOk();
});
