<?php

use App\Models\User;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Support\Facades\Session;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Laravel\Fortify\Fortify;

test('two factor authentication settings page can be accessed', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withoutMiddleware(RequirePassword::class)
        ->get(route('settings.two-factor.edit'))
        ->assertOk();
});

test('two factor authentication can be enabled', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withoutMiddleware(RequirePassword::class)
        ->put(route('settings.two-factor.update'));

    $user->refresh();

    $this->assertNotNull($user->two_factor_secret);
    $this->assertNull($user->two_factor_confirmed_at);
});

test('two factor authentication can be confirmed', function () {
    $user = User::factory()->withTwoFactorAuthenticationEnabled()->create();

    // Get the secret and generate a valid code
    $secret = decrypt($user->two_factor_secret);
    $google2fa = app(\PragmaRX\Google2FA\Google2FA::class);
    $validCode = $google2fa->getCurrentOtp($secret);

    // Confirm 2FA with valid code
    $this->actingAs($user)
        ->withoutMiddleware(RequirePassword::class)
        ->put(route('settings.confirmed-two-factor.update'), [
            'code' => $validCode,
        ]);

    $user->refresh();

    $this->assertNotNull($user->two_factor_confirmed_at);
    $this->assertNotNull($user->two_factor_recovery_codes);
});

test('cannot confirm two factor with invalid code', function ($code) {
    $user = User::factory()->create();

    // Enable 2FA
    $this->actingAs($user)
        ->put(route('settings.two-factor.update'));

    $user->refresh();

    // Confirm 2FA with valid code
    $this->actingAs($user)
        ->withoutMiddleware(RequirePassword::class)
        ->put(route('settings.confirmed-two-factor.update'), [
            'code' => $code,
        ])
        ->assertInvalid(['code']);

    $user->refresh();

    $this->assertNull($user->two_factor_confirmed_at);
})->with([
    'min length' => ['00000'],
    'required' => [''],
    'invalid code' => ['000000'],
]);

test('can view recovery codes', function () {
    $user = User::factory()->withTwoFactorAuthenticationEnabled()->create();

    $this->actingAs($user)
        ->withoutMiddleware(RequirePassword::class)
        ->get(route('settings.recovery-codes.edit'))
        ->assertOk();
});

test('can regenerate recovery codes', function () {
    $user = User::factory()->withTwoFactorAuthenticationEnabled()->create();

    $originalRecoveryCodes = $user->two_factor_recovery_codes;

    $this->actingAs($user)
        ->withoutMiddleware(RequirePassword::class)
        ->put(route('settings.recovery-codes.update'));

    $user->refresh();

    $this->assertNotEquals($originalRecoveryCodes, $user->two_factor_recovery_codes);
});

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

test('can disable two factor authentication', function () {
    $user = User::factory()->withTwoFactorAuthenticationEnabled()->create();

    $this->assertNotNull($user->two_factor_secret);
    $this->assertNotNull($user->two_factor_confirmed_at);
    $this->assertNotNull($user->two_factor_recovery_codes);

    $this->actingAs($user)
        ->withoutMiddleware(RequirePassword::class)
        ->delete(route('settings.two-factor.destroy'));

    $user->refresh();

    $this->assertNull($user->two_factor_secret);
    $this->assertNull($user->two_factor_confirmed_at);
    $this->assertNull($user->two_factor_recovery_codes);
});
