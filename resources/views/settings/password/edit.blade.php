<x-layouts.app :title="__('Update password')">
    <section class="w-full lg:max-w-lg mx-auto">
        @unlesshotwirenative
        <x-back-link :href="route('settings')">{{ __('Profile & Settings') }}</x-back-link>
        <x-text.heading size="xl">{{ __('Update password') }}</x-text.heading>
        @endunlesshotwirenative
        <x-text.subheading>{{ __('Ensure your account is using a long, random password to stay secure') }}</x-text.subheading>

        <x-page-card class="my-6">
            <form action="{{ route('settings.password.update') }}" method="post" class="space-y-6" data-controller="bridge--form" data-action="turbo:submit-start->bridge--form#submitStart turbo:submit-end->bridge--form#submitEnd">
                @csrf
                @method('put')

                <!-- Current Password -->
                <div>
                    <x-form.label for="current_password">{{ __('Current password') }}</x-form.label>

                    <x-form.password-input
                        id="current_password"
                        name="current_password"
                        :data-error="$errors->has('current_password')"
                        required
                        autofocus
                        autocomplete="current-password"
                        :placeholder="__('Current password')"
                        class="mt-2"
                    />

                    <x-form.error :message="$errors->first('current_password')" />
                </div>

                <!-- New Password -->
                <div>
                    <x-form.label for="password">{{ __('New password') }}</x-form.label>

                    <x-form.password-input
                        id="password"
                        name="password"
                        :data-error="$errors->has('password')"
                        required
                        autocomplete="new-password"
                        :placeholder="__('New password')"
                        class="mt-2"
                    />

                    <x-form.error :message="$errors->first('password')" />
                </div>

                <!-- Password Confirmation -->
                <div>
                    <x-form.label for="password_confirmation">{{ __('Confirm password') }}</x-form.label>

                    <x-form.password-input
                        id="password_confirmation"
                        name="password_confirmation"
                        :data-error="$errors->has('password_confirmation')"
                        required
                        autocomplete="new-password"
                        :placeholder="__('Confirm password')"
                        class="mt-2"
                    />

                    <x-form.error :message="$errors->first('password_confirmation')" />
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-end">
                        <x-form.button.primary type="submit" class="w-full" data-bridge--form-target="submit" data-bridge-title="{{ __('Save') }}">{{ __('Save') }}</x-form.button.primary>
                    </div>
                </div>
            </form>
        </x-page-card>
    </section>
</x-layouts.app>
