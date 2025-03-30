<?php

use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('settings', [SettingsController::class, 'show'])
        ->name('settings');

    Route::prefix('settings')->as('settings.')->group(function () {
        Route::singleton('profile', ProfileController::class)->only(['edit', 'update']);
        Route::get('profile/delete', [ProfileController::class, 'delete'])->name('profile.delete');
        Route::post('profile/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::singleton('password', PasswordController::class)->only(['edit', 'update']);
    });

    Route::singleton('theme', ThemeController::class)->only(['update']);
});

Route::get('configurations/android_v1', function () {
    return response()->json([
        'patterns' => [
            [
                'patterns' => ['.*'],
                'properties' => [
                    'uri' => 'hotwire://fragment/web',
                    'pull_to_refresh_enabled' => true,
                ],
            ],
            [
                'patterns' => ['/create/?$', '/edit/?$', '/delete/?$', '/login/?$'],
                'properties' => [
                    'context' => 'modal',
                    'pull_to_refresh_enabled' => false,
                ],
            ],
        ],
    ]);
});

require __DIR__.'/auth.php';
