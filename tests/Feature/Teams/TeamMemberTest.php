<?php

use App\Enums\TeamRole;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('team members index page can be rendered by members', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($owner, ['role' => TeamRole::Owner->value]);
    $team->members()->attach($member, ['role' => TeamRole::Member->value]);

    $this->actingAs($member)
        ->get(route('settings.teams.members.index', $team))
        ->assertOk();
});

test('team members index page denies non-members', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create();

    $this->actingAs($user)
        ->get(route('settings.teams.members.index', $team))
        ->assertForbidden();
});

test('team member role can be updated by owner', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($owner, ['role' => TeamRole::Owner->value]);
    $team->members()->attach($member, ['role' => TeamRole::Member->value]);

    $this->actingAs($owner)
        ->put(route('settings.teams.members.update', [$team, $member]), ['role' => 'admin'])
        ->assertValid()
        ->assertSessionHas('notice', 'Member role updated.');

    expect($member->fresh()->teamRole($team))->toBe(TeamRole::Admin);
});

test('team member role cannot be updated by admin', function () {
    $owner = User::factory()->create();
    $admin = User::factory()->create();
    $member = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($owner, ['role' => TeamRole::Owner->value]);
    $team->members()->attach($admin, ['role' => TeamRole::Admin->value]);
    $team->members()->attach($member, ['role' => TeamRole::Member->value]);

    $this->actingAs($admin)
        ->put(route('settings.teams.members.update', [$team, $member]), ['role' => 'admin'])
        ->assertForbidden();

    expect($member->fresh()->teamRole($team))->toBe(TeamRole::Member);
});

test('team member role cannot be set to owner via update', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($owner, ['role' => TeamRole::Owner->value]);
    $team->members()->attach($member, ['role' => TeamRole::Member->value]);

    $this->actingAs($owner)
        ->put(route('settings.teams.members.update', [$team, $member]), ['role' => 'owner'])
        ->assertInvalid(['role']);

    expect($member->fresh()->teamRole($team))->toBe(TeamRole::Member);
});

test('team member update requires a valid role', function (string $role) {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($owner, ['role' => TeamRole::Owner->value]);
    $team->members()->attach($member, ['role' => TeamRole::Member->value]);

    $this->actingAs($owner)
        ->put(route('settings.teams.members.update', [$team, $member]), ['role' => $role])
        ->assertInvalid(['role']);
})->with([
    'empty' => [''],
    'bogus' => ['superuser'],
]);

test('team member can be removed by owner', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($owner, ['role' => TeamRole::Owner->value]);
    $team->members()->attach($member, ['role' => TeamRole::Member->value]);

    $this->actingAs($owner)
        ->delete(route('settings.teams.members.destroy', [$team, $member]))
        ->assertSessionHas('notice', 'Member removed.');

    expect($member->fresh()->belongsToTeam($team))->toBeFalse();
});

test('team member cannot be removed by admin', function () {
    $owner = User::factory()->create();
    $admin = User::factory()->create();
    $member = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($owner, ['role' => TeamRole::Owner->value]);
    $team->members()->attach($admin, ['role' => TeamRole::Admin->value]);
    $team->members()->attach($member, ['role' => TeamRole::Member->value]);

    $this->actingAs($admin)
        ->delete(route('settings.teams.members.destroy', [$team, $member]))
        ->assertForbidden();

    expect($member->fresh()->belongsToTeam($team))->toBeTrue();
});

test('team member cannot be updated from a different team', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $teamA = Team::factory()->create();
    $teamB = Team::factory()->create();
    $teamA->members()->attach($owner, ['role' => TeamRole::Owner->value]);
    $teamB->members()->attach($owner, ['role' => TeamRole::Owner->value]);
    $teamB->members()->attach($member, ['role' => TeamRole::Member->value]);

    $this->actingAs($owner)
        ->put(route('settings.teams.members.update', [$teamA, $member]), ['role' => 'admin'])
        ->assertNotFound();

    expect($member->fresh()->teamRole($teamB))->toBe(TeamRole::Member);
});

test('team owner cannot demote themselves', function () {
    $owner = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($owner, ['role' => TeamRole::Owner->value]);

    $this->actingAs($owner)
        ->put(route('settings.teams.members.update', [$team, $owner]), ['role' => 'member'])
        ->assertForbidden();

    expect($owner->fresh()->teamRole($team))->toBe(TeamRole::Owner);
});

test('team owner cannot demote another owner', function () {
    $ownerA = User::factory()->create();
    $ownerB = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($ownerA, ['role' => TeamRole::Owner->value]);
    $team->members()->attach($ownerB, ['role' => TeamRole::Owner->value]);

    $this->actingAs($ownerA)
        ->put(route('settings.teams.members.update', [$team, $ownerB]), ['role' => 'admin'])
        ->assertForbidden();

    expect($ownerB->fresh()->teamRole($team))->toBe(TeamRole::Owner);
});

test('team owner cannot remove themselves', function () {
    $owner = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($owner, ['role' => TeamRole::Owner->value]);

    $this->actingAs($owner)
        ->delete(route('settings.teams.members.destroy', [$team, $owner]))
        ->assertForbidden();

    expect($owner->fresh()->belongsToTeam($team))->toBeTrue();
});

test('team owner cannot remove another owner', function () {
    $ownerA = User::factory()->create();
    $ownerB = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($ownerA, ['role' => TeamRole::Owner->value]);
    $team->members()->attach($ownerB, ['role' => TeamRole::Owner->value]);

    $this->actingAs($ownerA)
        ->delete(route('settings.teams.members.destroy', [$team, $ownerB]))
        ->assertForbidden();

    expect($ownerB->fresh()->belongsToTeam($team))->toBeTrue();
});

test('team member cannot be removed from a different team', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $teamA = Team::factory()->create();
    $teamB = Team::factory()->create();
    $teamA->members()->attach($owner, ['role' => TeamRole::Owner->value]);
    $teamB->members()->attach($owner, ['role' => TeamRole::Owner->value]);
    $teamB->members()->attach($member, ['role' => TeamRole::Member->value]);

    $this->actingAs($owner)
        ->delete(route('settings.teams.members.destroy', [$teamA, $member]))
        ->assertNotFound();

    expect($member->fresh()->belongsToTeam($teamB))->toBeTrue();
});
