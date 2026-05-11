<?php

use App\Enums\TeamRole;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Notifications\Teams\TeamInvitation as TeamInvitationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

test('team invitations index lists only pending invitations', function () {
    $owner = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($owner, ['role' => TeamRole::Owner->value]);

    $pending = TeamInvitation::factory()->for($team)->create([
        'email' => 'pending@example.com',
    ]);
    $accepted = TeamInvitation::factory()->for($team)->accepted()->create([
        'email' => 'accepted@example.com',
    ]);

    $response = $this->actingAs($owner)
        ->get(route('settings.teams.invitations.index', $team))
        ->assertOk();

    $response->assertSee('pending@example.com')
        ->assertDontSee('accepted@example.com');
});

test('team invitations index denies non-members', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create();

    $this->actingAs($user)
        ->get(route('settings.teams.invitations.index', $team))
        ->assertForbidden();
});

test('team invitations create page can be rendered by owner', function () {
    $owner = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($owner, ['role' => TeamRole::Owner->value]);

    $this->actingAs($owner)
        ->get(route('settings.teams.invitations.create', $team))
        ->assertOk();
});

test('team invitations create page denies plain members', function () {
    $member = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($member, ['role' => TeamRole::Member->value]);

    $this->actingAs($member)
        ->get(route('settings.teams.invitations.create', $team))
        ->assertForbidden();
});

test('team invitations can be created', function () {
    Notification::fake();

    $owner = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($owner, ['role' => TeamRole::Owner->value]);

    $this->actingAs($owner)
        ->post(route('settings.teams.invitations.store', $team), [
            'email' => 'invited@example.com',
            'role' => 'member',
        ])
        ->assertValid()
        ->assertRedirect(route('settings.teams.invitations.index', $team))
        ->assertSessionHas('notice', 'Invitation sent.');

    $invitation = TeamInvitation::firstWhere('email', 'invited@example.com');

    expect($invitation)->not->toBeNull();
    expect($invitation->team_id)->toBe($team->id);
    expect($invitation->role)->toBe(TeamRole::Member);
    expect($invitation->invited_by)->toBe($owner->id);
    expect($invitation->expires_at)->not->toBeNull();

    Notification::assertSentTo(
        new AnonymousNotifiable,
        TeamInvitationNotification::class,
        fn ($notification, $channels, $notifiable) => $notifiable->routes['mail'] === 'invited@example.com'
            && $notification->invitation->is($invitation)
    );
});

test('team invitations cannot be created by members', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($owner, ['role' => TeamRole::Owner->value]);
    $team->members()->attach($member, ['role' => TeamRole::Member->value]);

    $this->actingAs($member)
        ->post(route('settings.teams.invitations.store', $team), [
            'email' => 'invited@example.com',
            'role' => 'member',
        ])
        ->assertForbidden();
});

test('team invitations email is required and must be valid', function (mixed $email) {
    $owner = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($owner, ['role' => TeamRole::Owner->value]);

    $this->actingAs($owner)
        ->post(route('settings.teams.invitations.store', $team), [
            'email' => $email,
            'role' => 'member',
        ])
        ->assertInvalid(['email']);
})->with([
    'empty' => [''],
    'not-an-email' => ['not-an-email'],
]);

test('team invitations reject email of existing member', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create(['email' => 'taken@example.com']);
    $team = Team::factory()->create();
    $team->members()->attach($owner, ['role' => TeamRole::Owner->value]);
    $team->members()->attach($member, ['role' => TeamRole::Member->value]);

    $this->actingAs($owner)
        ->post(route('settings.teams.invitations.store', $team), [
            'email' => 'TAKEN@example.com',
            'role' => 'member',
        ])
        ->assertInvalid(['email']);
});

test('team invitations reject duplicate pending invitation', function () {
    $owner = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($owner, ['role' => TeamRole::Owner->value]);

    TeamInvitation::factory()->for($team)->create([
        'email' => 'invited@example.com',
        'expires_at' => now()->addDay(),
    ]);

    $this->actingAs($owner)
        ->post(route('settings.teams.invitations.store', $team), [
            'email' => 'INVITED@example.com',
            'role' => 'member',
        ])
        ->assertInvalid(['email']);
});

test('team invitations role is required and must be valid', function (mixed $role) {
    $owner = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($owner, ['role' => TeamRole::Owner->value]);

    $this->actingAs($owner)
        ->post(route('settings.teams.invitations.store', $team), [
            'email' => 'invited@example.com',
            'role' => $role,
        ])
        ->assertInvalid(['role']);
})->with([
    'empty' => [''],
    'bogus' => ['superuser'],
]);

test('team invitations can be cancelled by owner', function () {
    $owner = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($owner, ['role' => TeamRole::Owner->value]);

    $invitation = TeamInvitation::factory()->for($team)->create([
        'invited_by' => $owner->id,
    ]);

    $this->actingAs($owner)
        ->delete(route('settings.teams.invitations.destroy', [$team, $invitation]))
        ->assertRedirect(route('settings.teams.invitations.index', $team))
        ->assertSessionHas('notice', 'Invitation cancelled.');

    $this->assertModelMissing($invitation);
});

test('team invitations cannot be cancelled by plain members', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $team = Team::factory()->create();
    $team->members()->attach($owner, ['role' => TeamRole::Owner->value]);
    $team->members()->attach($member, ['role' => TeamRole::Member->value]);

    $invitation = TeamInvitation::factory()->for($team)->create();

    $this->actingAs($member)
        ->delete(route('settings.teams.invitations.destroy', [$team, $invitation]))
        ->assertForbidden();

    $this->assertModelExists($invitation);
});

test('team invitations can be accepted', function () {
    $owner = User::factory()->create();
    $invitedUser = User::factory()->create(['email' => 'invited@example.com']);
    $team = Team::factory()->create();
    $team->members()->attach($owner, ['role' => TeamRole::Owner->value]);

    $invitation = TeamInvitation::factory()->for($team)->create([
        'email' => 'invited@example.com',
        'role' => TeamRole::Member,
        'invited_by' => $owner->id,
        'expires_at' => now()->addDay(),
    ]);

    $this->actingAs($invitedUser)
        ->get(route('invitations.accept.show', $invitation))
        ->assertRedirect(route('dashboard'))
        ->assertSessionHas('notice', fn ($notice) => str_contains($notice, $team->name));

    expect($invitation->fresh()->accepted_at)->not->toBeNull();
    expect($invitedUser->fresh()->belongsToTeam($team))->toBeTrue();
    expect($invitedUser->fresh()->teamRole($team))->toBe(TeamRole::Member);
    expect($invitedUser->fresh()->current_team_id)->toBe($team->id);
});

test('team invitations cannot be accepted by user that wasnt invited', function () {
    $owner = User::factory()->create();
    $uninvitedUser = User::factory()->create(['email' => 'uninvited@example.com']);
    $team = Team::factory()->create();
    $team->members()->attach($owner, ['role' => TeamRole::Owner->value]);

    $invitation = TeamInvitation::factory()->for($team)->create([
        'email' => 'invited@example.com',
        'invited_by' => $owner->id,
        'expires_at' => now()->addDay(),
    ]);

    $this->actingAs($uninvitedUser)
        ->get(route('invitations.accept.show', $invitation))
        ->assertInvalid(['invitation']);

    expect($invitation->fresh()->accepted_at)->toBeNull();
    expect($uninvitedUser->fresh()->belongsToTeam($team))->toBeFalse();
});

test('expired invitations cannot be accepted', function () {
    $owner = User::factory()->create();
    $invitedUser = User::factory()->create(['email' => 'invited@example.com']);
    $team = Team::factory()->create();
    $team->members()->attach($owner, ['role' => TeamRole::Owner->value]);

    $invitation = TeamInvitation::factory()->for($team)->expired()->create([
        'email' => 'invited@example.com',
        'invited_by' => $owner->id,
    ]);

    $this->actingAs($invitedUser)
        ->get(route('invitations.accept.show', $invitation))
        ->assertInvalid(['invitation']);

    expect($invitedUser->fresh()->belongsToTeam($team))->toBeFalse();
});

test('already accepted invitations cannot be accepted again', function () {
    $owner = User::factory()->create();
    $invitedUser = User::factory()->create(['email' => 'invited@example.com']);
    $team = Team::factory()->create();
    $team->members()->attach($owner, ['role' => TeamRole::Owner->value]);

    $invitation = TeamInvitation::factory()->for($team)->accepted()->create([
        'email' => 'invited@example.com',
        'invited_by' => $owner->id,
        'expires_at' => now()->addDay(),
    ]);

    $previousAcceptedAt = $invitation->accepted_at;

    $this->actingAs($invitedUser)
        ->get(route('invitations.accept.show', $invitation))
        ->assertInvalid(['invitation']);

    expect($invitation->fresh()->accepted_at->equalTo($previousAcceptedAt))->toBeTrue();
});

test('guests cannot accept invitations', function () {
    $team = Team::factory()->create();
    $invitation = TeamInvitation::factory()->for($team)->create();

    $this->get(route('invitations.accept.show', $invitation))
        ->assertRedirect(route('login'));
});
