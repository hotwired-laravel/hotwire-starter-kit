<?php

use App\Models\User;
use Illuminate\Support\Facades\Session;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Laravel\Fortify\Fortify;

test('two factor authentication settings page can be accessed', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('settings.two-factor.edit'))->assertOk();
});

test('two factor authentication can be enabled', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->put(route('settings.two-factor.update'));

    $user->refresh();

    $this->assertNotNull($user->two_factor_secret);
    $this->assertNull($user->two_factor_confirmed_at);
});

test('two factor authentication can be confirmed', function () {
    $user = User::factory()->create();

    // Enable 2FA
    $this->actingAs($user)
        ->put(route('settings.two-factor.update'));

    $user->refresh();

    // Get the secret and generate a valid code
    $secret = decrypt($user->two_factor_secret);
    $google2fa = app(\PragmaRX\Google2FA\Google2FA::class);
    $validCode = $google2fa->getCurrentOtp($secret);

    // Confirm 2FA with valid code
    $this->actingAs($user)
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
    $user = createUserWithTwoFactorEnabled();

    $this->actingAs($user)
        ->get(route('settings.recovery-codes.edit'))
        ->assertOk();
});

test('can regenerate recovery codes', function () {
    $user = createUserWithTwoFactorEnabled();

    $originalRecoveryCodes = $user->two_factor_recovery_codes;

    $this->actingAs($user)
        ->put(route('settings.recovery-codes.update'));

    $user->refresh();

    $this->assertNotEquals($originalRecoveryCodes, $user->two_factor_recovery_codes);
});

test('user with two factor authentication enabled is redirected to challenge on login', function () {
    $user = createUserWithTwoFactorEnabled();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect('/two-factor-challenge');
    $this->assertEquals($user->id, Session::get('login.id'));
});

test('can login with two factor code', function () {
    $user = createUserWithTwoFactorEnabled();

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
    $user = createUserWithTwoFactorEnabled();

    $this->actingAs($user)
        ->delete(route('settings.two-factor.destroy'));

    $user->refresh();

    $this->assertNull($user->two_factor_secret);
    $this->assertNull($user->two_factor_confirmed_at);
    $this->assertNull($user->two_factor_recovery_codes);
});

function createUserWithTwoFactorEnabled(): User
{
    $secretLength = (int) config('fortify-options.two-factor-authentication.secret-length', 16);

    $user = User::factory()->create();

    // Enable two-factor authentication for the user
    $user->forceFill([
        'two_factor_secret' => Fortify::currentEncrypter()->encrypt(resolve(TwoFactorAuthenticationProvider::class)->generateSecretKey($secretLength)),
        'two_factor_recovery_codes' => encrypt(json_encode([
            'recovery-code-1',
            'recovery-code-2',
            'recovery-code-3',
            'recovery-code-4',
            'recovery-code-5',
            'recovery-code-6',
            'recovery-code-7',
            'recovery-code-8',
        ])),
        'two_factor_confirmed_at' => now(),
    ])->save();

    return $user;
}
