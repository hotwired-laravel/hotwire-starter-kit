<x-layouts.auth :title="__('Authentication code')">
    <div class="flex flex-col gap-6">
        <input type="checkbox" name="recovery_toggle" id="recovery_toggle" @if ($errors->has('recovery_code')) checked @endif class="peer hidden" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form action="{{ route('two-factor.login.store') }}" method="post" class="flex peer-checked:hidden flex-col gap-6" data-turbo-action="replace">
            @csrf

            <x-auth-header :title="__('Authentication Code')" :description="__('Enter the authentication code provided by your authenticator application.')" />

            <!-- Authentication Code -->
            <div>
                <x-form.label for="code" class="sr-only">{{ __('OTP Code') }}</x-form.label>

                <x-form.otp-input id="code" name="code" :value="old('code')" :data-error="$errors->has('code')" />

                <x-form.error :message="$errors->first('code')" />
            </div>

            <div class="flex items-center justify-end">
                <x-form.button.primary type="submit" class="w-full">{{ __('Continue') }}</x-form.button.primary>
            </div>

            <div class="space-x-0.5 text-sm leading-5 text-center">
                <span class="opacity-50">{{ __('or you can') }}</span>
                <div class="inline font-medium underline cursor-pointer opacity-80">
                    <label for="recovery_toggle" role="button" class="cursor-pointer">{{ __('login using a recovery code') }}</label>
                </div>
            </div>
        </form>

        <form action="{{ route('two-factor.login.store') }}" method="post" class="hidden peer-checked:flex flex-col gap-6" data-turbo-action="replace">
            @csrf

            <x-auth-header :title="__('Recovery Code')" :description="__('Please confirm access to your account by entering one of your emergency recovery codes.')" />

            <!-- Recovery Code -->
            <div>
                <x-form.label for="recovery_code" class="sr-only">{{ __('Recovery Code') }}</x-form.label>

                <x-form.text-input id="recovery_code" name="recovery_code" type="text" :value="old('recovery_code')" :data-error="$errors->has('recover_code')" />

                <x-form.error :message="$errors->first('recovery_code')" />
            </div>

            <div class="flex items-center justify-end">
                <x-form.button.primary type="submit" class="w-full">{{ __('Continue') }}</x-form.button.primary>
            </div>

            <div class="space-x-0.5 text-sm leading-5 text-center">
                <span class="opacity-50">{{ __('or you can') }}</span>
                <div class="inline font-medium underline cursor-pointer opacity-80">
                    <label for="recovery_toggle" role="button" class="cursor-pointer">{{ __('login using an authentication code') }}</label>
                </div>
            </div>
        </form>
    </div>
</x-layouts.auth>
