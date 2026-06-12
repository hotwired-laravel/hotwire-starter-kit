<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;

class ConfirmedTwoFactorController extends Controller
{
    public function edit(Request $request): View
    {
        return view('settings.confirmed-two-factor.edit', [
            'user' => $user = $request->user(),
            'qrCodeSvg' => $user->twoFactorQrCodeSvg(),
            'setupCode' => decrypt($user->two_factor_secret),
        ]);
    }

    public function update(Request $request, ConfirmTwoFactorAuthentication $confirmTwoFactor): RedirectResponse
    {
        $input = $request->validate([
            'code' => ['required', 'min:6'],
        ]);

        try {
            $confirmTwoFactor($request->user(), $input['code']);
        } catch (ValidationException $e) {
            throw $e->errorBag('default');
        }

        return redirect()->route('settings.two-factor.edit')->with('notice', __('Two-factor confirmed.'));
    }
}
