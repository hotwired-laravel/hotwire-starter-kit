<?php

use App\Models\User;
use Illuminate\Support\Facades\Session;
use Laravel\Fortify\Fortify;

test('user with two factor authentication enabled is redirected to challenge on login', function () {
    $user = User::factory()->withTwoFactorAuthenticationEnabled()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect('/two-factor-challenge');
    $this->assertEquals($user->id, Session::get('login.id'));
});

test('can login with two factor code', function () {
    $user = User::factory()->withTwoFactorAuthenticationEnabled()->create();

    // Set up session as if user just entered credentials
    Session::put([
        'login.id' => $user->id,
        'login.remember' => false,
    ]);

    // Generate valid 2FA code
    $secret = Fortify::currentEncrypter()->decrypt($user->two_factor_secret);
    $google2fa = app(\PragmaRX\Google2FA\Google2FA::class);
    $validCode = $google2fa->getCurrentOtp($secret);

    $response = $this->post('/two-factor-challenge', [
        'code' => $validCode,
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user);
});

test('2fa is rate limited', function () {
    $user = User::factory()->withTwoFactorAuthenticationEnabled()->create();

    // Set up session as if user just entered credentials
    Session::put([
        'login.id' => $user->id,
        'login.remember' => false,
    ]);

    foreach (range(1, 5) as $_) {
        $this->post('/two-factor-challenge', [
            'code' => '123123',
        ])->assertRedirect(route('two-factor.login'))->assertInvalid('code');
    }

    $this->post('/two-factor-challenge', [
        'code' => '123123',
    ])->assertTooManyRequests();
});

test('can login with recovery codes', function () {
    $user = User::factory()->withTwoFactorAuthenticationEnabled()->create();

    // Set up session as if user just entered credentials
    Session::put([
        'login.id' => $user->id,
        'login.remember' => false,
    ]);

    $recoveryCode = json_decode(decrypt($user->two_factor_recovery_codes), true)[0];

    $response = $this->post('/two-factor-challenge', [
        'recovery_code' => $recoveryCode,
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user);

    $this->assertNotContains($recoveryCode, json_decode(decrypt($user->fresh()->two_factor_recovery_codes), true));
});
