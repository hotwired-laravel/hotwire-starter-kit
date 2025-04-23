@props(['transitions' => true, 'scalable' => false, 'title' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if (session('theme')) data-theme="{{ session('theme') }}" @endif @class(["min-h-screen bg-base-300", "hotwire-native" => Turbo::isHotwireNativeVisit()]) >
    <head>
        @include('partials.head', [
            'transitions' => $transitions,
            'scalable' => $scalable,
            'title' => $title,
        ])
    </head>
    <body data-controller="sidebar theme" data-theme-active-class="btn-active [&_svg]:visible!" data-action="turbo:before-cache@document->sidebar#close">
        <x-drawer id="main-sidebar">
            <x-slot name="checkbox">
                <input id="main-sidebar" data-sidebar-target="checkbox" type="checkbox" class="drawer-toggle" />
            </x-slot>

            <div class="w-full min-h-screen flex">
                <aside class="hidden lg:flex lg:flex-col max-w-[250px] w-full p-4 space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <x-app-logo />
                    </a>

                    <div class="space-y-2 mt-4">
                        <span class="text-base-content/50 text-xs px-1">{{ __('Platform') }}</span>
                        <x-sidebar.navlist class="px-0">
                            <x-sidebar.navlist-item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-sidebar.navlist-item>
                        </x-sidebar.navlist>
                    </div>

                    <div class="flex-1"></div>

                    <x-sidebar.navlist class="px-0">
                        <x-sidebar.navlist-item icon="code-bracket" target="_blank" href="https://github.com/hotwired-laravel/hotwire-starter-kit">{{ __('Repository') }}</x-sidebar.navlist-item>
                        <x-sidebar.navlist-item icon="book-open" target="_blank" href="https://turbo-laravel.com">{{ __('Documentation') }}</x-sidebar.navlist-item>
                    </x-sidebar.navlist>

                    <x-sidebar.navlist class="px-0">
                        <x-sidebar.navlist-item :href="route('settings')" :current="request()->routeIs(['settings', 'settings.*'])">
                            <x-slot name="iconSection">
                                <x-profile :initials="auth()->user()->initials()" class="p-0!" />
                            </x-slot>
                            <span>{{ auth()->user()->name }}</span>
                        </x-sidebar.navlist-item>
                    </x-sidebar.navlist>
                </aside>

                @include('partials.notifications')

                <div class="flex-1 m-0 lg:m-2 rounded shadow bg-base-200 lg:border lg:dark:border-white/5 lg:border-black/10">
                    <div class="flex ml-2 mr-6 mt-2 lg:hidden items-center space-x-2 justify-between">
                        <div class="flex items-center space-x-2">
                            <x-drawer.toggle for="main-sidebar" icon="bars-3" class="lg:hidden px-2.5 [&>div>svg]:size-5! [&>div>svg]:mr-0! h-10" />

                            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                                <x-app-logo />
                            </a>
                        </div>

                        <ul class="flex items-center space-x-2">
                            <x-navbar.item icon="magnifying-glass" href="#" class="px-2.5 [&>div>svg]:size-5! [&>div>svg]:mr-0! h-10 text-base-content/50">
                                <span class="sr-only">{{ __('Search') }}</span>
                            </x-navbar.item>

                            <a href="{{ route('settings') }}">
                                <x-profile :initials="auth()->user()->initials()" class="p-0!" />
                                <span class="sr-only">{{ auth()->user()->name }}</span>
                            </a>
                        </ul>
                    </div>

                    {{ $slot }}
                </div>
            </div>

            <x-slot name="aside">
                <div class="flex items-center space-x-2">
                    <x-drawer.toggle for="main-sidebar" icon="x-mark" class="lg:hidden px-2 [&>div>svg]:size-5! [&>div>svg]:mr-0! h-10" />
                </div>

                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 px-1.5 my-4">
                    <x-app-logo />
                </a>

                <x-sidebar.navlist class="px-0">
                    <x-sidebar.navlist-item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-sidebar>
                </x-sidebar.navlist>

                <div class="flex-1 w-0"></div>

                <x-sidebar.navlist class="p-0">
                    <x-sidebar.navlist-item icon="code-bracket" target="_blank" href="https://github.com/hotwired-laravel/hotwire-starter-kit">{{ __('Repository') }}</x-sidebar.navlist-item>
                    <x-sidebar.navlist-item icon="book-open" target="_blank" href="https://turbo-laravel.com">{{ __('Documentation') }}</x-sidebar.navlist-item>
                </x-sidebar.navlist>
            </x-slot>
        </x-drawer>
    </body>
</html>
