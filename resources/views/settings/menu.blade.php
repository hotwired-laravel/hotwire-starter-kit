<x-layouts.app :title="__('Profile & Settings')">
    <section class="w-full max-w-md mx-auto space-y-6">
        @unlesshotwirenative
            <x-text.heading size="xl">{{ __('Profile & Settings') }}</x-text.heading>
        @endunlesshotwirenative

        <x-menu>
            <x-menu.link icon="user" :href="route('settings.profile.edit')">{{ __('Edit profile') }}</x-menu.link>
            <x-menu.link icon="key" :href="route('settings.password.edit')">{{ __('Change password') }}</x-menu.link>
            <x-menu.link icon="trash" :href="route('settings.profile.delete')">{{ __('Delete profile') }}</x-menu.link>
        </x-menu>

        <x-menu>
            <x-menu.button icon="chevron-left" form="settings-logout" type="submit">{{ __('Logout') }}</x-menu>
        </x-menu>
    </section>

    <form action="{{ route('logout') }}" method="post" id="settings-logout" data-turbo-action="replace" data-action="submit->theme#clear">
        @csrf
    </form>
</x-layouts.app>
