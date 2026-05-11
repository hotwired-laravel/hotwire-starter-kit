<?php

use App\Enums\TeamRole;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

test('getRouteKeyName returns code', function () {
    expect((new TeamInvitation)->getRouteKeyName())->toBe('code');
});

test('creating auto-generates a 64-char code', function () {
    $invitation = TeamInvitation::factory()->create();

    expect($invitation->code)->toBeString()->toHaveLength(64);
});

test('creating respects an explicitly provided code', function () {
    $invitation = TeamInvitation::factory()->create([
        'code' => str_repeat('a', 64),
    ]);

    expect($invitation->code)->toBe(str_repeat('a', 64));
});

test('team and inviter relations resolve', function () {
    $inviter = User::factory()->create();
    $team = Team::factory()->create();

    $invitation = TeamInvitation::factory()->create([
        'team_id' => $team->id,
        'invited_by' => $inviter->id,
    ]);

    expect($invitation->team->is($team))->toBeTrue();
    expect($invitation->inviter->is($inviter))->toBeTrue();
});

test('role casts to TeamRole enum', function () {
    $invitation = TeamInvitation::factory()->create([
        'role' => TeamRole::Admin,
    ]);

    expect($invitation->role)->toBe(TeamRole::Admin);
});

test('expires_at and accepted_at cast to Carbon', function () {
    $invitation = TeamInvitation::factory()->create([
        'expires_at' => now()->addDay(),
        'accepted_at' => now(),
    ]);

    expect($invitation->expires_at)->toBeInstanceOf(Carbon::class);
    expect($invitation->accepted_at)->toBeInstanceOf(Carbon::class);
});

test('state methods reflect invitation status', function (?string $expires, ?string $accepted, bool $isAccepted, bool $isPending, bool $isExpired) {
    $invitation = TeamInvitation::factory()->create([
        'expires_at' => $expires ? Carbon::parse($expires) : null,
        'accepted_at' => $accepted ? Carbon::parse($accepted) : null,
    ]);

    expect($invitation->isAccepted())->toBe($isAccepted);
    expect($invitation->isPending())->toBe($isPending);
    expect($invitation->isExpired())->toBe($isExpired);
})->with([
    'pending with future expiry' => ['+1 day', null, false, true, false],
    'pending with null expiry never expires' => [null, null, false, true, false],
    'expired and not yet accepted' => ['-1 day', null, false, false, true],
    'accepted invitation' => ['+1 day', 'now', true, false, false],
]);

test('pending scope returns only invitations without accepted_at', function () {
    $team = Team::factory()->create();

    $pending = TeamInvitation::factory()->for($team)->create();
    TeamInvitation::factory()->for($team)->accepted()->create();

    $invitations = TeamInvitation::query()->pending()->get();

    expect($invitations)->toHaveCount(1);
    expect($invitations->first()->is($pending))->toBeTrue();
});
