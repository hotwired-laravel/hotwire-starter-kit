<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;

class TwoFactorController extends Controller
{
    /**
     * Show the form for enabling two-factor authentication.
     */
    public function edit(Request $request): View
    {
        return view('settings.two-factor.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Enable two-factor authentication for the user.
     */
    public function update(Request $request, EnableTwoFactorAuthentication $enableTwoFactor): RedirectResponse
    {
        $enableTwoFactor($request->user());

        return redirect()->route('settings.confirmed-two-factor.edit');
    }

    /**
     * Disable two-factor authentication for the user.
     */
    public function destroy(Request $request, DisableTwoFactorAuthentication $disableTwoFactor): RedirectResponse
    {
        $disableTwoFactor($request->user());

        return redirect()->back(fallback: route('settings.two-factor.edit'))->with('notice', __('Two-factor disabled.'));
    }
}
