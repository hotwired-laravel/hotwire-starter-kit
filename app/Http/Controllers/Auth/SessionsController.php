<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Features;

class SessionsController extends Controller
{
    /**
     * Shows the login form.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handles the login submit.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $this->ensureIsNotRateLimited($request);

        $user = $this->validateCredentials($request);

        if (Features::enabled(Features::twoFactorAuthentication()) && $user->hasEnabledTwoFactorAuthentication()) {
            Session::put([
                'login.id' => $user->id,
                'login.remember' => $request->boolean('remember'),
            ]);

            return redirect()->route('two-factor.login');
        }

        Auth::login($user);

        RateLimiter::clear($this->throttleKey($request));
        Session::regenerate();

        return redirect()->intended(default: route('dashboard', absolute: false));
    }

    /**
     * Handles the logout submit.
     */
    public function destroy()
    {
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        if (request()->wasFromHotwireNative()) {
            return redirect(route('login'));
        }

        return redirect('/');
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
    }

    private function validateCredentials(Request $request): User
    {
        /** @var User $user */
        $user = Auth::getProvider()->retrieveByCredentials(['email' => $request->email, 'password' => $request->password]);

        if (! $user || ! Auth::getProvider()->validateCredentials($user, ['password' => $request->password])) {
            RateLimiter::hit($this->throttleKey($request));

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        return $user;
    }
}
