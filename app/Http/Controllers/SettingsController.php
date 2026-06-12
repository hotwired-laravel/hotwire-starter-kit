<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class SettingsController extends Controller
{
    /**
     * Show the settings menu.
     */
    public function show(): View
    {
        return view('settings.menu');
    }
}
