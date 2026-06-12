<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;

class RecoveryCodesController extends Controller
{
    /**
     * Show the form for managing recovery codes.
     */
    public function edit(Request $request): View
    {
        return view('settings.recovery-codes.edit', [
            'user' => $user = $request->user(),
            'recoveryCodes' => json_decode(decrypt($user->two_factor_recovery_codes), true),
        ]);
    }

    /**
     * Generate new recovery codes for the user.
     */
    public function update(Request $request, GenerateNewRecoveryCodes $generateRecoveryCodes): RedirectResponse
    {
        $generateRecoveryCodes($request->user());

        return redirect()->route('settings.recovery-codes.edit')->with('notice', __('New recovery codes generated.'));
    }
}
