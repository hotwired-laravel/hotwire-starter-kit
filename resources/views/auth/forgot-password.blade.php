<x-layouts.auth :title="__('Forgot password')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Forgot password')" :description="__('Enter your email to receive a password reset link')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form action="{{ route('password.email') }}" method="post" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <div>
                <x-form.label for="email">{{ __('Email address') }}</x-form.label>

                <x-form.text-input id="email" type="email" name="email" :value="old('email')" :data-error="$errors->has('email')"
                    required autofocus autocomplete="email" placeholder="email@example.com" class="mt-2" />

                <x-form.error :message="$errors->first('email')" />
            </div>

            <x-form.button.primary type="submit"
                class="w-full">{{ __('Email password reset link') }}</x-form.button.primary>
        </form>

        <div class="space-x-1 text-center text-sm text-base-400">
            <span>{{ __('Or, return to') }}</span>
            <x-link :href="route('login')">{{ __('log in') }}</x-link>
        </div>
    </div>
</x-layouts.auth>
