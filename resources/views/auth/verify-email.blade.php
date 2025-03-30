<x-layouts.auth :title="__('Verify email')">
    <div class="mt-4 flex flex-col gap-6">
        <x-text class="text-center">
            {{ __('Please verify your email address by clicking on the link we just emailed to you.') }}
        </x-text>

        @if (session('status') == 'verification-link-sent')
            <x-text class="text-center font-medium !dark:text-green-400 !text-green-600">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </x-text>
        @endif

        <div class="flex flex-col items-center justify-between space-y-3">
            <form action="{{ route('verification.resend') }}" method="post">
                @csrf

                <x-form.button.primary class="w-full">
                    {{ __('Resend verification email') }}
                </x-form.button.primary>
            </form>

            <form action="{{ route('logout') }}" method="post">
                @csrf

                <x-form.button.link class="text-sm cursor-pointer">
                    {{ __('Log out') }}
                </x-form.button.link>
            </form>
        </div>
    </div>
</x-layouts.auth>
