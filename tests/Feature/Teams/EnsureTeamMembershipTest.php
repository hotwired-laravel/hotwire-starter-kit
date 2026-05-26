<?php

use App\Enums\TeamRole;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('visiting another team dashboard does not persist current_team_id', function () {
    $user = User::factory()->create();
    $personal = $user->personalTeam();
    $other = Team::factory()->create();
    $other->members()->attach($user, ['role' => TeamRole::Member->value]);

    $this->actingAs($user)
        ->get(route('dashboard', ['current_team' => $other->slug]))
        ->assertOk();

    expect($user->fresh()->current_team_id)->toBe($personal->id);
});

test('URL defaults follow the route slug, not the persisted current team', function () {
    $user = User::factory()->create();
    $other = Team::factory()->create();
    $other->members()->attach($user, ['role' => TeamRole::Member->value]);

    $this->actingAs($user)
        ->get(route('dashboard', ['current_team' => $other->slug]))
        ->assertOk();

    expect(route('dashboard'))->toBe(route('dashboard', ['current_team' => $other->slug]));
});

test('non-members still cannot reach a team dashboard', function () {
    $user = User::factory()->create();
    $foreign = Team::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard', ['current_team' => $foreign->slug]))
        ->assertForbidden();
});

test('active_team() prefers the route slug', function () {
    $user = User::factory()->create();
    $other = Team::factory()->create();
    $other->members()->attach($user, ['role' => TeamRole::Member->value]);

    $this->actingAs($user)->get(route('dashboard', ['current_team' => $other->slug]));

    expect(active_team()->is($other))->toBeTrue();
});

test('active_team() falls back to currentTeam outside of a team route', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('settings'));

    expect(active_team()->is($user->personalTeam()))->toBeTrue();
});

test('active_team() returns null for guests', function () {
    $this->get(route('home'));

    expect(active_team())->toBeNull();
});
