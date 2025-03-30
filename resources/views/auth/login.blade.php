<x-layouts.auth :title="__('Login')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form action="{{ route('login.store') }}" method="post" class="flex flex-col gap-6" data-turbo-action="replace">
            @csrf

            <!-- Email Address -->
            <div>
                <x-form.label for="email">{{ __('Email address') }}</x-form.label>

                <x-form.text-input id="email" type="email" name="email" :value="old('email')" :data-error="$errors->has('email')"
                    required autofocus autocomplete="email" placeholder="email@example.com" class="mt-2" />

                <x-form.error :message="$errors->first('email')" />
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between space-x-1">
                    <x-form.label for="password">{{ __('Password') }}</x-form.label>

                    @if (Route::has('password.request'))
                        <x-link class="text-sm" :href="route('password.request')">
                            {{ __('Forgot your password?') }}
                        </x-link>
                    @endif
                </div>

                <x-form.password-input id="password" name="password" required autocomplete="current-password"
                    :placeholder="__('Password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <x-form.checkbox id="remember_me" :label="__('Remember me')" name="remember" />

            <div class="flex items-center justify-end">
                <x-form.button.primary type="submit" class="w-full">{{ __('Log in') }}</x-form.button.primary>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="space-x-1 text-center text-sm text-base-600">
                {{ __('Don\'t have an account?') }}
                <x-link :href="route('register')" wire:navigate>{{ __('Sign up') }}</x-link>
            </div>
        @endif
    </div>
</x-layouts.auth>
