<x-layouts.app :title="__('Delete profile')">
    <section class="w-full lg:max-w-lg mx-auto">
        @unlesshotwirenative
        <x-back-link :href="route('settings')">{{ __('Profile & Settings') }}</x-back-link>
        <x-text.heading size="xl">{{ __('Delete profile') }}</x-text.heading>
        @endunlesshotwirenative
        <x-text.subheading>{{ __('Delete your account and all of its resources') }}</x-text.subheading>

        <x-page-card class="my-6">
            <p class="text-sm">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}</p>

            <form action="{{ route('settings.profile.destroy') }}" method="post" class="mt-6 w-full space-y-6" data-controller="bridge--form" data-action="turbo:submit-start->bridge--form#submitStart turbo:submit-end->bridge--form#submitEnd">
                @csrf

                <!-- Current Password -->
                <div>
                    <x-form.label for="password">{{ __('Current password') }}</x-form.label>

                    <x-form.password-input
                        id="password"
                        type="password"
                        name="password"
                        :data-error="$errors->has('password')"
                        required
                        autofocus
                        autocomplete="current-password"
                        :placeholder="__('Current password')"
                        class="mt-2"
                    />

                    <x-form.error :message="$errors->first('password')" />
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-end">
                        <x-form.button.danger type="submit" class="w-full" data-bridge--form-target="submit" data-bridge-title="{{ __('Delete') }}" data-bridge-destructive="true">{{ __('Delete account') }}</x-form.button.danger>
                    </div>
                </div>
            </form>
        </x-page-card>
    </section>
</x-layouts.app>
