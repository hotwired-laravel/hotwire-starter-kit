<?php

use App\Enums\TeamRole;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can switch to a team the user belongs to', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($user, ['role' => TeamRole::Member->value]);

    $this->actingAs($user)
        ->get(route('settings.teams.switch.show', $team))
        ->assertRedirect(route('dashboard', ['current_team' => $team->slug]));

    expect($user->fresh()->current_team_id)->toBe($team->id);
});

test('switch redirects to dashboard when to_dashboard=true even with a referer', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($user, ['role' => TeamRole::Member->value]);

    $this->actingAs($user)
        ->withHeaders(['Referer' => 'http://localhost/some/page'])
        ->get(route('settings.teams.switch.show', $team).'?to_dashboard=1')
        ->assertRedirect(route('dashboard', ['current_team' => $team->slug]));
});

test('switch rewrites the team slug in the referer URL', function () {
    $user = User::factory()->create();
    $previousTeam = $user->personalTeam();
    $newTeam = Team::factory()->create(['name' => 'Brand New']);
    $newTeam->members()->attach($user, ['role' => TeamRole::Member->value]);

    $referer = "http://localhost/{$previousTeam->slug}/dashboard";

    $this->actingAs($user)
        ->withHeaders(['Referer' => $referer])
        ->get(route('settings.teams.switch.show', $newTeam))
        ->assertRedirect("http://localhost/{$newTeam->slug}/dashboard");
});

test('cannot switch to a team the user does not belong to', function () {
    $user = User::factory()->create();
    $foreign = Team::factory()->create();

    $this->actingAs($user)
        ->get(route('settings.teams.switch.show', $foreign))
        ->assertForbidden();

    expect($user->fresh()->current_team_id)->not->toBe($foreign->id);
});
