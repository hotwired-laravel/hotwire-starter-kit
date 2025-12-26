<x-layouts.app :title="__('Recovery codes')">
    <section class="w-full lg:max-w-lg mx-auto">
        @unlesshotwirenative
        <x-back-link :href="route('settings.two-factor.edit')">{{ __('Two-factor authentication') }}</x-back-link>
        <x-text.heading size="xl">{{ __('Recovery codes') }}</x-text.heading>
        @endunlesshotwirenative
        <x-text.subheading>{{ __('Manage your 2FA recovery codes') }}</x-text.subheading>

        <x-page-card class="my-6">
            @if ($user->hasEnabledTwoFactorAuthentication())
                <div class="relative flex flex-col items-start rounded-xl justify-start space-y-4">
                    <div class="badge badge-soft badge-success">{{ __('Enabled') }}</div>

                    <p class="-translate-y-1 text-sm text-base-content dark:text-base-content/70">{{ __('With two-factor authentication enabled, you will be prompted for a secure, random pin during login, which you can retrieve from the TOTP-supported application on your phone.') }}</p>

                    <div class="w-full card bg-base-100 border border-base-200">
                        <div class="card-body space-y-2">
                            <h2 class="card-title">
                                <x-heroicon-o-lock-closed class="h-4 w-4" />
                                <span>{{ __('2FA Recovery Codes') }}</span>
                            </h2>

                            <p class="text-base-content/50">{{ __('Recovery codes let you regain access if you lose your 2FA device. Store them in a secure password manager.') }}</p>

                            <div class="card-actions">
                                <x-turbo::frame id="recovery-codes" target="_top">
                                    <div class="flex items-center justify-between space-x-4">
                                        <a href="{{ route('settings.two-factor.edit') }}" class="btn btn-soft space-x-1">
                                            <x-heroicon-o-eye-slash class="h-4 w-4 shrink-0" />
                                            <span class="whitespace-nowrap">{{ __('Hide Recovery Codes') }}</span>
                                        </a>

                                        <form class="block" action="{{ route('settings.recovery-codes.update') }}" method="post" data-turbo-confirm="{{ __('Are you sure you want to regenerate the codes?') }}">
                                            @csrf
                                            @method('PUT')

                                            <button type="submit" class="btn btn-ghost">
                                                <x-heroicon-o-arrow-path class="h-4 w-4 shrink-0" />
                                                <span class="whitespace-nowrap">{{ __('Regenerate Codes') }}</span>
                                            </button>
                                        </form>
                                    </div>

                                    <div
                                        class="mt-4 grid gap-1 p-4 font-mono text-sm rounded-lg bg-zinc-100 dark:bg-white/5"
                                        role="list"
                                        aria-label="Recovery codes"
                                    >
                                        @foreach($recoveryCodes as $code)
                                            <div role="listitem" class="select-text">
                                                {{ $code }}
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mt-4">
                                        <p class="text-base-content/50 text-xs">{{ __('Each recovery code can be used once to access your account and will be removed after use. If you need more, click Regenerate Codes above.') }}</p>
                                    </div>
                                </x-turbo::frame>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </x-page-card>
    </section>
</x-layouts.app>
