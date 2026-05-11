<?php

use App\Enums\TeamRole;

test('roles have human labels', function (TeamRole $role, string $label) {
    expect($role->label())->toBe($label);
})->with([
    'owner' => [TeamRole::Owner, 'Owner'],
    'admin' => [TeamRole::Admin, 'Admin'],
    'member' => [TeamRole::Member, 'Member'],
]);

test('owner permissions include all team and member actions', function () {
    expect(TeamRole::Owner->permissions())->toEqual([
        'team:update',
        'team:delete',
        'member:update',
        'member:remove',
        'invitation:create',
        'invitation:cancel',
    ]);
});

test('admin permissions are limited to update and invitations', function () {
    expect(TeamRole::Admin->permissions())->toEqual([
        'team:update',
        'invitation:create',
        'invitation:cancel',
    ]);
});

test('member permissions are empty', function () {
    expect(TeamRole::Member->permissions())->toEqual([]);
});

test('hasPermission checks the role permission map', function (TeamRole $role, string $permission, bool $expected) {
    expect($role->hasPermission($permission))->toBe($expected);
})->with([
    'owner can delete team' => [TeamRole::Owner, 'team:delete', true],
    'admin cannot delete team' => [TeamRole::Admin, 'team:delete', false],
    'admin can update team' => [TeamRole::Admin, 'team:update', true],
    'member cannot update team' => [TeamRole::Member, 'team:update', false],
    'member has no permissions' => [TeamRole::Member, 'invitation:create', false],
    'unknown permission' => [TeamRole::Owner, 'totally:made-up', false],
]);

test('roles have a hierarchy level', function (TeamRole $role, int $level) {
    expect($role->level())->toBe($level);
})->with([
    'owner is 3' => [TeamRole::Owner, 3],
    'admin is 2' => [TeamRole::Admin, 2],
    'member is 1' => [TeamRole::Member, 1],
]);

test('isAtLeast compares hierarchy', function () {
    expect(TeamRole::Owner->isAtLeast(TeamRole::Admin))->toBeTrue();
    expect(TeamRole::Owner->isAtLeast(TeamRole::Owner))->toBeTrue();
    expect(TeamRole::Admin->isAtLeast(TeamRole::Member))->toBeTrue();
    expect(TeamRole::Admin->isAtLeast(TeamRole::Owner))->toBeFalse();
    expect(TeamRole::Member->isAtLeast(TeamRole::Admin))->toBeFalse();
});

test('assignable roles exclude owner', function () {
    $assignable = TeamRole::assignable();

    expect($assignable)->toHaveCount(2);
    expect(collect($assignable)->pluck('value')->all())->toEqual(['admin', 'member']);
    expect($assignable[0])->toEqual(['value' => 'admin', 'label' => 'Admin']);
    expect($assignable[1])->toEqual(['value' => 'member', 'label' => 'Member']);
});
