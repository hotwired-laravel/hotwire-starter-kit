<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;

class RecoveryCodesController extends Controller
{
    public function edit(Request $request)
    {
        return view('settings.recovery-codes.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request, GenerateNewRecoveryCodes $generateRecoveryCodes)
    {
        $generateRecoveryCodes($request->user());

        return redirect()->back(fallback: route('settings.recovery-codes.edit'))->with('notice', __('New recovery codes generated.'));
    }
}
