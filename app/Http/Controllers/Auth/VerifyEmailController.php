<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Shows the verify email page.
     */
    public function show()
    {
        return view('auth.verify-email');
    }

    /**
     * Resends the email verification email.
     */
    public function store()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect()->intended(default: route('dashboard', absolute: false));
        }

        Auth::user()->sendEmailVerificationNotification();

        return redirect()->back()->with('notice', __('Verification email was sent.'))->with('verification-sent', true);
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function update(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            /** @var \Illuminate\Contracts\Auth\MustVerifyEmail $user */
            $user = $request->user();

            event(new Verified($user));
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
