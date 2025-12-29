<?php

use App\Http\Controllers\HotwireNativeConfigurationController;
use App\Http\Controllers\Settings\ConfirmedTwoFactorController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\RecoveryCodesController;
use App\Http\Controllers\Settings\TwoFactorController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('settings', [SettingsController::class, 'show'])->name('settings');

    Route::prefix('settings')->as('settings.')->group(function () {
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

    Route::singleton('theme', ThemeController::class)->only(['update']);
});

Route::get('configurations/android_v1', [HotwireNativeConfigurationController::class, 'index']);

require __DIR__.'/auth.php';
