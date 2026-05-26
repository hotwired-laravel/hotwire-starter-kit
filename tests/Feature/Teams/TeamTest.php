<?php

use App\Enums\TeamRole;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('teams index page can be rendered', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('settings.teams.index'))
        ->assertOk();
});

test('guests cannot access teams', function () {
    $this->get(route('settings.teams.index'))
        ->assertRedirect(route('login'));
});

test('teams can be created', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('settings.teams.store'), ['team_name' => 'Test Team'])
        ->assertValid()
        ->assertSessionHas('notice', 'Team created.');

    $team = Team::firstWhere('name', 'Test Team');

    expect($team)->not->toBeNull();
    expect($team->is_personal)->toBeFalse();
    expect($user->fresh()->current_team_id)->toBe($team->id);
    expect($user->fresh()->ownsTeam($team))->toBeTrue();
});

test('team slug uses next available suffix', function () {
    $user = User::factory()->create();

    Team::factory()->create(['name' => 'Acme', 'slug' => 'acme']);
    Team::factory()->create(['name' => 'Acme One', 'slug' => 'acme-1']);
    Team::factory()->create(['name' => 'Acme Ten', 'slug' => 'acme-10']);

    $this->actingAs($user)
        ->post(route('settings.teams.store'), ['team_name' => 'Acme'])
        ->assertValid();

    $this->assertDatabaseHas('teams', [
        'name' => 'Acme',
        'slug' => 'acme-11',
    ]);
});

test('team creation requires a name', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('settings.teams.store'), ['team_name' => ''])
        ->assertInvalid(['team_name']);
});

test('team creation rejects reserved names', function (string $name) {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('settings.teams.store'), ['team_name' => $name])
        ->assertInvalid(['team_name']);
})->with([
    'admin' => ['admin'],
    'settings' => ['settings'],
    'login' => ['login'],
    'http status code' => ['404'],
    'uppercase' => ['SETTINGS'],
    'leading/trailing whitespace' => ['  settings  '],
    'diacritic settings' => ['settïngs'],
    'diacritic admin' => ['admín'],
    'capitalized diacritic' => ['Settïngs'],
    'zero-width space' => ["settings\u{200B}"],
]);

test('team creation rejects names that slugify to empty', function (string $name) {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('settings.teams.store'), ['team_name' => $name])
        ->assertInvalid(['team_name']);
})->with([
    'punctuation' => ['!!'],
    'dots' => ['...'],
    'emoji' => ['🎉🎉'],
]);

test('team update rejects reserved names via unicode', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['name' => 'Original Name', 'slug' => 'original-name']);
    $team->members()->attach($user, ['role' => TeamRole::Owner->value]);

    $this->actingAs($user)
        ->put(route('settings.teams.update', $team), ['team_name' => 'settïngs'])
        ->assertInvalid(['team_name']);

    $team->refresh();

    expect($team->name)->toBe('Original Name');
    expect($team->slug)->toBe('original-name');
});

test('team edit page can be rendered by owner', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($user, ['role' => TeamRole::Owner->value]);

    $this->actingAs($user)
        ->get(route('settings.teams.edit', $team))
        ->assertOk();
});

test('team show page can be rendered by member', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($user, ['role' => TeamRole::Member->value]);

    $this->actingAs($user)
        ->get(route('settings.teams.show', $team))
        ->assertOk();
});

test('teams show page denies non-members', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create();

    $this->actingAs($user)
        ->get(route('settings.teams.show', $team))
        ->assertForbidden();
});

test('teams can be updated by owners', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['name' => 'Original Name']);
    $team->members()->attach($user, ['role' => TeamRole::Owner->value]);

    $this->actingAs($user)
        ->put(route('settings.teams.update', $team), ['team_name' => 'Updated Name'])
        ->assertValid()
        ->assertRedirect(route('settings.teams.show', $team->fresh()))
        ->assertSessionHas('notice', 'Team name updated.');

    $team->refresh();

    expect($team->name)->toBe('Updated Name');
    expect($team->slug)->toBe('updated-name');
});

test('teams cannot be updated by members', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['name' => 'Original Name']);
    $team->members()->attach($user, ['role' => TeamRole::Member->value]);

    $this->actingAs($user)
        ->put(route('settings.teams.update', $team), ['team_name' => 'Updated Name'])
        ->assertForbidden();

    expect($team->fresh()->name)->toBe('Original Name');
});

test('teams update requires a name', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($user, ['role' => TeamRole::Owner->value]);

    $this->actingAs($user)
        ->put(route('settings.teams.update', $team), ['team_name' => ''])
        ->assertInvalid(['team_name']);
});

test('admins can update team', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['name' => 'Original']);
    $team->members()->attach($user, ['role' => TeamRole::Admin->value]);

    $this->actingAs($user)
        ->put(route('settings.teams.update', $team), ['team_name' => 'Renamed'])
        ->assertValid();

    expect($team->fresh()->name)->toBe('Renamed');
});
