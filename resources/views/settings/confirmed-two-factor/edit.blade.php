<x-layouts.app :title="__('Enable Two-Factor Authentication')">
    <section class="w-full lg:max-w-lg mx-auto">
        @unlesshotwirenative
        <x-back-link :href="route('settings.two-factor.edit')">{{ __('Two-factor authentication') }}</x-back-link>
        <x-text.heading size="xl">{{ __('Enable Two-Factor Authentication') }}</x-text.heading>
        @endunlesshotwirenative

        <x-page-card class="my-6">
            <x-turbo::frame id="two-factor-enable" class="relative flex flex-col items-start rounded-xl justify-start space-y-4">
                <div class="badge badge-soft badge-error">{{ __('Disabled') }}</div>

                <input type="checkbox" class="sr-only peer" id="continue" @if (old('continue')) checked @endif form="confirm-two-factor-form" name="continue" />

                <div class="peer-checked:hidden flex flex-col items-start justify-start space-y-4">
                    <p class="-translate-y-1 text-sm text-base-content dark:text-base-content/70">{{ __('To finish enabling two-factor authentication, scan the QR code or enter the setup key in your authenticator app.') }}</p>

                    <div class="flex items-center justify-center h-full border border-base-content/20 rounded-xl p-4">
                        <div class="bg-white p-3 rounded">
                            {!! $qrCodeSvg !!}
                        </div>
                    </div>

                    <label for="continue" role="button" class="btn btn-primary">Continue</label>

                    <div class="divider text-sm text-base-content/70">{{ __('or, enter the code manually') }}</div>

                    <div class="join group" data-controller="clipboard">
                        <input class="input join-item" name="setup_code" type="text" data-clipboard-target="source" value="{{ $setupCode }}" readonly />

                        <button type="button" class="btn join-item rounded-r-full" data-action="clipboard#copy">
                            <x-heroicon-o-clipboard class="group-data-[clipboard-copied-value=true]:hidden h-4 w-4 text-white" />
                            <x-heroicon-o-check class="hidden group-data-[clipboard-copied-value=true]:inline text-green-500 h-4 w-4" />

                            <span class="sr-only">{{ __('Copy') }}</span>
                        </button>
                    </div>
                </div>

                <form id="confirm-two-factor-form" action="{{ route('settings.confirmed-two-factor.update') }}" method="post" class="hidden peer-checked:flex flex-col items-start justify-start space-y-4">
                    @csrf
                    @method('PUT')

                    <p class="-translate-y-1 text-sm text-base-content dark:text-base-content/70">{{ __('Enter the 6-digit code from your authenticator app.') }}</p>

                    <div>
                        <x-form.label for="code" class="sr-only">{{ __('Code') }}</x-form.label>

                        <x-form.text-input id="code" type="text" name="code" :value="old('code')" :data-error="$errors->has('code')"
                            required autofocus autocomplete="off" />

                        <x-form.error :message="$errors->first('code')" />
                    </div>

                    <div class="flex items-center justify-between space-x-4">
                        <label for="continue" role="button" class="btn btn-block">{{ __('Back') }}</label>

                        <button type="submit" class="btn btn-block btn-primary">
                            {{ __('Confirm') }}
                        </button>
                    </div>
                </form>
            </x-turbo::frame>
        </x-page-card>
    </section>
</x-layouts.app>
