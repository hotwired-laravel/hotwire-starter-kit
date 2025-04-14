<?php

namespace App\Providers;

use HotwiredLaravel\TurboLaravel\Facades\Turbo;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Turbo::usePartialsSubfolderPattern();
    }
}
