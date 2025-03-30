<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function update(Request $request)
    {
        $request->session()->put('theme', $request->input('theme'));

        if ($request->wantsTurboStream()) {
            return response()->noContent();
        }

        return redirect()->back()->with('notice', __('Theme updated.'));
    }
}
