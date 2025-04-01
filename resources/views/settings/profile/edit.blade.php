<x-layouts.app :title="__('Profile')">
    <section class="w-full lg:max-w-lg mx-auto">
        @unlesshotwirenative
        <x-back-link :href="route('settings')">{{ __('Profile & Settings') }}</x-back-link>
        <x-text.heading size="xl">{{ __('Profile') }}</x-text.heading>
        @endunlesshotwirenative
        <x-text.subheading>{{ __('Update your name and email address') }}</x-text.subheading>

        <x-page-card class="my-6">
            <form action="{{ route('settings.profile.update') }}" method="post" class="w-full space-y-6" data-controller="bridge--form" data-action="turbo:submit-start->bridge--form#submitStart turbo:submit-end->bridge--form#submitEnd">
                @csrf
                @method('put')

                <!-- Name -->
                <div>
                    <x-form.label for="name">{{ __('Name') }}</x-form.label>

                    <x-form.text-input
                        id="name"
                        name="name"
                        :value="old('name', $name)"
                        :data-error="$errors->has('name')"
                        required
                        autofocus
                        autocomplete="name"
                        :placeholder="__('Full name')"
                        class="mt-2"
                    />

                    <x-form.error :message="$errors->first('name')" />
                </div>

                <!-- Email Address -->
                <div>
                    <x-form.label for="email">{{ __('Email address') }}</x-form.label>

                    <x-form.text-input
                        id="email"
                        name="email"
                        type="email"
                        :value="old('email', $email)"
                        :data-error="$errors->has('email')"
                        required
                        autocomplete="email"
                        :placeholder="__('email@example.com')"
                        class="mt-2"
                    />

                    <x-form.error :message="$errors->first('email')" />

                    @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                        <div>
                            <x-text class="mt-4">
                                {{ __('Your email address is unverified.') }}

                                <x-form.button.link form="resend-email-verification" class="text-sm cursor-pointer">
                                    {{ __('Click here to re-send the verification email.') }}
                                </x-form.button.link>
                            </x-text>

                            @if (session('status') === 'verification-link-sent')
                                <x-text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </x-text>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-end">
                        <x-form.button.primary type="submit" class="w-full" data-bridge--form-target="submit" data-bridge-title="{{ __('Save') }}">{{ __('Save') }}</x-form.button.primary>
                    </div>
                </div>
            </form>
        </x-page-card>

        <form action="{{ route('verification.resend') }}" method="post" id="resend-email-verification">
            @csrf
        </form>
    </section>
</x-layouts.app>
