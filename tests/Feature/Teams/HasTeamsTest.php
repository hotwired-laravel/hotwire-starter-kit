<?php

use App\Enums\TeamRole;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('teams relation returns all teams the user belongs to', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($user, ['role' => TeamRole::Admin->value]);

    expect($user->teams)->toHaveCount(2);
    expect($user->teams->contains($team))->toBeTrue();

    $loadedTeam = $user->teams->firstWhere('id', $team->id);
    expect($loadedTeam->membership->role)->toBe(TeamRole::Admin);
});

test('memberships relation returns user membership rows', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($user, ['role' => TeamRole::Member->value]);

    expect($user->memberships()->count())->toBe(2);
    expect($user->memberships()->where('team_id', $team->id)->first()->role)->toBe(TeamRole::Member);
});

test('currentTeam returns the user current team', function () {
    $user = User::factory()->create();

    expect($user->currentTeam->is($user->personalTeam()))->toBeTrue();
});

test('personalTeam returns the auto-created personal team', function () {
    $user = User::factory()->create();

    $personal = $user->personalTeam();

    expect($personal)->not->toBeNull();
    expect($personal->is_personal)->toBeTrue();
    expect($personal->name)->toBe($user->name."'s Team");
});

test('personalTeam returns null when there is no personal team', function () {
    $user = User::factory()->create();

    $user->personalTeam()->forceDelete();

    expect($user->personalTeam())->toBeNull();
});

test('switchTeam updates current_team_id when user belongs', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($user, ['role' => TeamRole::Member->value]);

    $result = $user->switchTeam($team);

    expect($result)->toBeTrue();
    expect($user->fresh()->current_team_id)->toBe($team->id);
});

test('switchTeam returns false and leaves state unchanged when not a member', function () {
    $user = User::factory()->create();
    $originalCurrentTeamId = $user->current_team_id;
    $team = Team::factory()->create();

    $result = $user->switchTeam($team);

    expect($result)->toBeFalse();
    expect($user->fresh()->current_team_id)->toBe($originalCurrentTeamId);
});

test('belongsToTeam reflects membership', function () {
    $user = User::factory()->create();
    $member = Team::factory()->create();
    $member->members()->attach($user, ['role' => TeamRole::Member->value]);
    $outsider = Team::factory()->create();

    expect($user->belongsToTeam($member))->toBeTrue();
    expect($user->belongsToTeam($outsider))->toBeFalse();
});

test('isCurrentTeam compares ids', function () {
    $user = User::factory()->create();
    $other = Team::factory()->create();
    $other->members()->attach($user, ['role' => TeamRole::Member->value]);

    expect($user->isCurrentTeam($user->personalTeam()))->toBeTrue();
    expect($user->isCurrentTeam($other))->toBeFalse();
});

test('ownsTeam true only for Owner role', function (TeamRole $role, bool $owns) {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($user, ['role' => $role->value]);

    expect($user->ownsTeam($team))->toBe($owns);
})->with([
    'owner' => [TeamRole::Owner, true],
    'admin' => [TeamRole::Admin, false],
    'member' => [TeamRole::Member, false],
]);

test('teamRole returns the enum case or null', function () {
    $user = User::factory()->create();
    $member = Team::factory()->create();
    $member->members()->attach($user, ['role' => TeamRole::Admin->value]);
    $outsider = Team::factory()->create();

    expect($user->teamRole($member))->toBe(TeamRole::Admin);
    expect($user->teamRole($outsider))->toBeNull();
});

test('fallbackTeam returns alphabetically first team case-insensitive', function () {
    $user = User::factory()->create();
    $user->personalTeam()->forceDelete();

    foreach (['beta', 'Alpha', 'carlos'] as $name) {
        $team = Team::factory()->create(['name' => $name]);
        $team->members()->attach($user, ['role' => TeamRole::Owner->value]);
    }

    expect($user->fresh()->fallbackTeam()->name)->toBe('Alpha');
});

test('fallbackTeam can exclude a given team', function () {
    $user = User::factory()->create();
    $user->personalTeam()->forceDelete();

    $alpha = Team::factory()->create(['name' => 'Alpha']);
    $alpha->members()->attach($user, ['role' => TeamRole::Owner->value]);
    $beta = Team::factory()->create(['name' => 'Beta']);
    $beta->members()->attach($user, ['role' => TeamRole::Owner->value]);

    expect($user->fresh()->fallbackTeam(excluding: $alpha)->is($beta))->toBeTrue();
});

test('hasTeamPermission delegates to role permissions', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($user, ['role' => TeamRole::Owner->value]);
    $outsider = Team::factory()->create();

    expect($user->hasTeamPermission($team, 'team:update'))->toBeTrue();
    expect($user->hasTeamPermission($team, 'team:delete'))->toBeTrue();
    expect($user->hasTeamPermission($outsider, 'team:update'))->toBeFalse();
});

test('hasTeamPermission returns false for member without that permission', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($user, ['role' => TeamRole::Member->value]);

    expect($user->hasTeamPermission($team, 'team:update'))->toBeFalse();
});
