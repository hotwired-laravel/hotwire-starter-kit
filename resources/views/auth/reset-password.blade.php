<x-layouts.auth :title="__('Reset password')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Reset password')" :description="__('Please enter your new password below')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form action="{{ route('password.reset.update', request()->route('token')) }}" method="post"
            class="flex flex-col gap-6">
            @csrf
            @method('put')

            <!-- Email Address -->
            <div>
                <x-form.label for="email">{{ __('Email address') }}</x-form.label>

                <x-form.text-input id="email" type="email" name="email" :value="old('email', request('email'))" :data-error="$errors->has('email')"
                    required autofocus autocomplete="email" placeholder="email@example.com" class="mt-2" />

                <x-form.error :message="$errors->first('email')" />
            </div>

            <!-- Password -->
            <div>
                <x-form.label for="password">{{ __('Password') }}</x-form.label>

                <x-form.password-input id="password" name="password" required autocomplete="new-password"
                    :data-error="$errors->has('password')" :placeholder="__('Password')" class="mt-2" />

                <x-form.error :message="$errors->first('password')" />
            </div>

            <!-- Password Confirmation -->
            <div>
                <x-form.label for="password_confirmation">{{ __('Confirm password') }}</x-form.label>

                <x-form.password-input id="password_confirmation" name="password_confirmation" required
                    autocomplete="new-password" :placeholder="__('Confirm password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end">
                <x-form.button.primary type="submit" class="w-full">
                    {{ __('Reset password') }}
                </x-form.button.primary>
            </div>
        </form>
    </div>
</x-layouts.auth>
