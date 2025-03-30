<x-layouts.auth :title="__('Confirm password')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Confirm password')" :description="__('This is a secure area of the application. Please confirm your password before continuing.')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form action="{{ route('password.confirm.store') }}" method="post" class="flex flex-col gap-6">
            @csrf

            <!-- Password -->
            <div>
                <x-form.label for="password">{{ __('Password') }}</x-form.label>

                <x-form.password-input id="password" name="password" required autocomplete="current-password"
                    :placeholder="__('Password')" :data-error="$errors->has('password')" class="mt-2" />

                <x-form.error :message="$errors->first('password')" />
            </div>

            <div class="flex items-center justify-end">
                <x-form.button.primary type="submit" class="w-full">{{ __('Confirm') }}</x-form.button.primary>
            </div>
        </form>
    </div>
</x-layouts.auth>
