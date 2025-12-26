<x-layouts.app :title="__('Two-factor authentication')">
    <x-turbo::exempts-page-from-cache />

    <section class="w-full lg:max-w-lg mx-auto">
        @unlesshotwirenative
        <x-back-link :href="route('settings')">{{ __('Profile & Settings') }}</x-back-link>
        <x-text.heading size="xl">{{ __('Two-factor authentication') }}</x-text.heading>
        @endunlesshotwirenative
        <x-text.subheading>{{ __('Manage your two-factor authentication settings') }}</x-text.subheading>

        <x-page-card class="my-6">
            @if ($user->hasEnabledTwoFactorAuthentication())
                <div class="relative flex flex-col items-start rounded-xl justify-start space-y-4">
                    <div class="badge badge-soft badge-success">{{ __('Enabled') }}</div>

                    <p class="-translate-y-1 text-sm text-base-content dark:text-base-content/70">{{ __('With two-factor authentication enabled, you will be prompted for a secure, random pin during login, which you can retrieve from the TOTP-supported application on your phone.') }}</p>

                    <div class="w-full card bg-base-100 border border-base-content/10">
                        <div class="card-body space-y-2">
                            <h2 class="card-title">
                                <x-heroicon-o-lock-closed class="h-4 w-4" />
                                <span>{{ __('2FA Recovery Codes') }}</span>
                            </h2>

                            <p class="text-base-content/50">{{ __('Recovery codes let you regain access if you lose your 2FA device. Store them in a secure password manager.') }}</p>

                            <div class="card-actions">
                                <x-turbo::frame id="recovery-codes" class="block w-full">
                                    <a href="{{ route('settings.recovery-codes.edit') }}" class="btn btn-soft space-x-1">
                                        <x-heroicon-o-eye class="h-4 w-4" />
                                        <span class="whitespace-nowrap">{{ __('Show Recovery Codes') }}</span>
                                    </a>
                                </x-turbo::frame>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('settings.two-factor.destroy') }}" data-turbo-confirm="{{ __('Are you sure you want to disable it?') }}" method="post">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-error text-error-content">
                            <x-heroicon-o-shield-check class="h-4 w-4" />

                            <span>{{ __('Disable 2FA') }}</span>
                        </button>
                    </form>
                </div>
            @else
                <x-turbo::frame id="two-factor-enable" data-controller="frame" data-action="turbo:frame-missing->frame#breakoutWhenMissing" class="relative flex flex-col items-start rounded-xl justify-start space-y-4">
                    <div class="badge badge-soft badge-error">{{ __('Disabled') }}</div>

                    <form action="{{ route('settings.two-factor.update') }}" method="post" class="flex flex-col items-start justify-start space-y-4">
                        @csrf
                        @method('PUT')

                        <p class="-translate-y-1 text-sm text-base-content dark:text-base-content/70">{{ __('When you enable two-factor authentication, you will be prompted for a secure pin during login. This pin can be retrieved from a TOTP-supported application on your phone.') }}</p>

                        <div>
                            <x-form.button.primary type="submit" class="w-full flex items-center space-x-0.5" data-bridge--form-target="submit" data-bridge-title="{{ __('Enable') }}">
                                <x-heroicon-o-shield-check class="h-4 w-4 text-white" />

                                <span class="whitespace-nowrap">{{ __('Enable 2FA') }}</span>
                            </x-form.button.primary>
                        </div>
                    </form>
                </x-turbo::frame>
            @endif
        </x-page-card>
    </section>
</x-layouts.app>
