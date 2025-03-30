<?php

namespace App\Http\Controllers;

class SettingsController extends Controller
{
    /**
     * Show the settings menu.
     */
    public function show()
    {
        return view('settings.menu');
    }
}
