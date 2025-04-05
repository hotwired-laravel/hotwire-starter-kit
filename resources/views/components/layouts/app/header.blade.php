@props(['transitions' => true, 'scalable' => false, 'title' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if (session('theme')) data-theme="{{ session('theme') }}" @endif >
    <head>
        @include('partials.head', [
            'transitions' => $transitions,
            'scalable' => $scalable,
            'title' => $title,
        ])
    </head>
    <body data-controller="sidebar theme" data-theme-active-class="btn-active [&_svg]:visible!" data-action="turbo:before-cache@document->sidebar#close" @class(["min-h-screen bg-base-200", "hotwire-native" => Turbo::isHotwireNativeVisit()])>
        <x-drawer id="main-sidebar">
            <x-slot name="checkbox">
                <input id="main-sidebar" data-sidebar-target="checkbox" type="checkbox" class="drawer-toggle" />
            </x-slot>

            @unlesshotwirenative
                <header class="min-h-14 flex items-center border-b border-base-300 bg-base-100">
                    <div class="mx-auto w-full h-full [:where(&)]:max-w-7xl px-6 lg:px-8 flex items-center">
                        <x-drawer.toggle for="main-sidebar" icon="bars-3" class="lg:hidden px-2.5 [&>div>svg]:size-5! [&>div>svg]:mr-0! h-10" />

                        <a href="{{ route('dashboard') }}" class="ml-2 mr-5 flex items-center space-x-2 lg:ml-0">
                            <x-app-logo />
                        </a>

                        <x-navbar class="-mb-px max-lg:hidden">
                            <x-navbar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')">
                                <span>{{ __('Dashboard') }}</span>
                            </x-navbar.item>
                        </x-navbar>

                        <div class="flex-1"></div>

                        <x-navbar class="mr-1.5 space-x-0.5 !py-0">
                            <x-navbar.item icon="magnifying-glass" href="#" class="px-2.5 [&>div>svg]:size-5! [&>div>svg]:mr-0! h-10">
                                <span class="sr-only">{{ __('Search') }}</span>
                            </x-navbar.item>

                            <x-navbar.item icon="code-bracket" target="_blank" href="https://github.com/hotwired-laravel/hotwire-starter-kit" class="px-2.5 [&>div>svg]:size-5! [&>div>svg]:mr-0! h-10 max-lg:hidden">
                                <span class="sr-only">{{ __('Repository') }}</span>
                            </x-navbar.item>

                            <x-navbar.item icon="open-book" target="_blank" href="https://turbo-laravel.com" class="px-2.5 [&>div>svg]:size-5! [&>div>svg]:mr-0! h-10 max-lg:hidden">
                                <span class="sr-only">{{ __('Documentation') }}</span>
                            </x-navbar.item>

                            <form action="{{ route('theme.update') }}" method="post" id="theme-form" data-action="submit->theme#updateFromSubmit">
                                @csrf
                                @method('PUT')

                                <button type="submit" class="sr-only">{{ __('Update Theme') }}</button>

                                <div class="dropdown dropdown-end">
                                    <x-navbar.item-button icon="paint-brush" class="px-2.5 [&>div>svg]:size-5! [&>div>svg]:mr-0! h-10 max-lg:hidden">
                                        <span class="sr-only">{{ __('Theme') }}</span>
                                    </x-navbar.item-button>

                                    <ul tabindex="0" class="dropdown-content max-h-[50vh] [:where(&_li:empty)]:h-[0.1em] [:where(&_li:empty)]:bg-base-100/90 [:where(&_li:empty)]:my-2 [:where(&_li:empty)]:mx-1 overflow-y-auto bg-base-300 rounded-box z-1 w-52 p-2 shadow-2xl">
                                        <li class="menu-title text-xs">Theme</li>
                                        @foreach (['default', 'light', 'dark', 'cupcake', 'synthwave', 'retro', 'halloween', 'forest', 'dracula', 'nord', 'silk', 'cyberpunk', 'valentine', 'emerald'] as $theme)
                                        <li>
                                            <button class="btn btn-ghost w-full flex gap-4 px-2" data-theme-target="button" type="submit" name="theme" value="{{ $theme }}">
                                                <div data-theme="{{ $theme }}" class="bg-base-100 grid shrink-0 grid-cols-2 gap-0.5 rounded-md p-1 shadow-sm">
                                                    <div class="bg-base-content size-1 rounded-full"></div>
                                                    <div class="bg-primary size-1 rounded-full"></div>
                                                    <div class="bg-secondary size-1 rounded-full"></div>
                                                    <div class="bg-accent size-1 rounded-full"></div>
                                                </div>

                                                <div class="w-32 truncate text-left">{{ $theme }}</div>

                                                <x-icons.check size="md" class="invisible" />
                                            </button>
                                        </li>
                                        @endforeach
                                        <li></li>
                                        <li>
                                            <a href="https://daisyui.com/theme-generator/" class="btn btn-ghost w-full flex gap-4 px-2" target="_blank">
                                                <x-icons.paint-brush size="md" class="fill-current" />

                                                <div class="grow text-left text-sm font-bold">{{ __('make your own!') }}</div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </form>
                        </x-navbar>

                        <!-- Desktop User Menu -->
                        <a href="{{ route('settings') }}">
                            <x-profile
                                class="cursor-pointer"
                                :initials="auth()->user()->initials()"
                            />
                        </a>
                    </div>
                </header>
            @endunlesshotwirenative

            @include('partials.notifications')

            {{ $slot }}

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
                    <x-sidebar.navlist-item icon="open-book" target="_blank" href="https://turbo-laravel.com">{{ __('Documentation') }}</x-sidebar.navlist-item>
                </x-sidebar.navlist>
            </x-slot>
        </x-drawer>
    </body>
</html>
