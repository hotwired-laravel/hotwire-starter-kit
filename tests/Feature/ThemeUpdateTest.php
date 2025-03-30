<?php

use App\Models\User;

test('authenticated users can update themes in session', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->put(route('theme.update'), [
        'theme' => 'halloween',
    ])->assertValid()->assertRedirect();

    expect(session('theme'))->toEqual('halloween');
});
