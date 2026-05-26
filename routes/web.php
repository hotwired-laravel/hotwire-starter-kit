<?php

use App\Http\Controllers\AcceptedInvitationsController;
use App\Http\Controllers\HotwireNativeConfigurationController;
use App\Http\Controllers\Settings\ConfirmedTwoFactorController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\RecoveryCodesController;
use App\Http\Controllers\Settings\TeamInvitationsController;
use App\Http\Controllers\Settings\TeamMembersController;
use App\Http\Controllers\Settings\TeamsController;
use App\Http\Controllers\Settings\TeamSwitchController;
use App\Http\Controllers\Settings\TwoFactorController;
use App\Http\Controllers\SettingsController;
use App\Http\Middleware\EnsureTeamMembership;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::view('/', 'welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::prefix('{current_team}')
        ->middleware(['auth', 'verified', EnsureTeamMembership::class])
        ->group(function () {
            Route::view('dashboard', 'dashboard')->name('dashboard');
        });

    Route::get('invitations/{invitation}/accept', [AcceptedInvitationsController::class, 'show'])
        ->middleware('signed')
        ->name('invitations.accept.show');

    Route::get('settings', [SettingsController::class, 'show'])->name('settings');

    Route::prefix('settings')->as('settings.')->group(function () {
        Route::resource('teams', TeamsController::class);
        Route::singleton('teams.switch', TeamSwitchController::class)->only(['update']);
        Route::resource('teams.members', TeamMembersController::class)->only(['index', 'update', 'destroy'])->scoped();
        Route::resource('teams.invitations', TeamInvitationsController::class)->only(['index', 'create', 'store', 'destroy'])->scoped();

        Route::singleton('profile', ProfileController::class)->only(['edit', 'update']);
        Route::get('profile/delete', [ProfileController::class, 'delete'])->name('profile.delete');
        Route::post('profile/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::singleton('password', PasswordController::class)->only(['edit', 'update']);

        if (Features::canManageTwoFactorAuthentication()) {
            Route::middleware(when(Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'), ['password.confirm'], []))->group(function () {
                Route::singleton('two-factor', TwoFactorController::class)->destroyable()->only(['edit', 'update', 'destroy']);
                Route::singleton('confirmed-two-factor', ConfirmedTwoFactorController::class)->only(['edit', 'update']);
                Route::singleton('recovery-codes', RecoveryCodesController::class)->only(['edit', 'update']);
            });
        }
    });
});

Route::get('configurations/android_v1', [HotwireNativeConfigurationController::class, 'index']);
