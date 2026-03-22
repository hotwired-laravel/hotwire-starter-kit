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
    <body data-controller="sidebar theme" data-layout="sidebar" data-theme-active-class="btn-active [&_svg]:visible!" data-action="turbo:before-cache@document->sidebar#close" @class(["min-h-screen antialiased bg-base-300", "hotwire-native" => Turbo::isHotwireNativeVisit()])>
        <x-drawer id="main-sidebar" class="min-h-screen">
            <x-slot name="checkbox">
                <input id="main-sidebar" data-sidebar-target="checkbox" type="checkbox" class="drawer-toggle" />
            </x-slot>

            <div class="w-full h-screen md:flex">
                {{--- Desktop nav ---}}
                <aside class="hidden md:flex md:flex-col max-w-[250px] w-full p-4 space-y-1 border-r border-black/10 dark:border-white/5">
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

                        <form action="{{ route('theme.update') }}" method="post" class="w-full" id="theme-form" data-action="submit->theme#updateFromSubmit">
                            @csrf
                            @method('PUT')

                            <button type="submit" class="sr-only">{{ __('Update Theme') }}</button>

                            <div class="dropdown dropdown-top dropdown-right w-full">
                                <x-sidebar.navlist-item icon="paint-brush" as="button" type="button" class="w-full">
                                    <span>{{ __('Theme') }}</span>
                                    <x-heroicon-o-chevron-up-down class="size-4" />
                                </x-sidebar.navlist-item>

                                <ul tabindex="0" class="dropdown-content max-h-[50vh] [:where(&_li:empty)]:h-[0.1em] [:where(&_li:empty)]:bg-base-100/90 [:where(&_li:empty)]:my-2 [:where(&_li:empty)]:mx-1 overflow-y-auto bg-base-300 rounded-box z-1 w-52 p-2 shadow-2xl">
                                    <li class="menu-title text-xs">Theme</li>
                                    @foreach (['default', 'acid', 'autumn', 'aqua', 'bumblebee', 'business', 'caramellatte', 'cmyk', 'coffee', 'corporate', 'cupcake', 'cyberpunk', 'dark', 'dim', 'dracula', 'emerald', 'fantasy', 'forest', 'garden', 'halloween', 'light', 'lofi', 'luxury', 'night', 'nord', 'retro', 'silk', 'synthwave', 'valentine'] as $theme)
                                    <li>
                                        <button class="btn btn-ghost w-full flex gap-4 px-2" data-theme-target="button" type="submit" name="theme" value="{{ $theme }}">
                                            <div data-theme="{{ $theme }}" class="bg-base-100 grid shrink-0 grid-cols-2 gap-0.5 rounded-md p-1 shadow-sm">
                                                <div class="bg-base-content size-1 rounded-full"></div>
                                                <div class="bg-primary size-1 rounded-full"></div>
                                                <div class="bg-secondary size-1 rounded-full"></div>
                                                <div class="bg-accent size-1 rounded-full"></div>
                                            </div>

                                            <div class="w-32 truncate text-left">{{ $theme }}</div>

                                            <x-heroicon-o-check class="size-4 invisible" />
                                        </button>
                                    </li>
                                    @endforeach
                                    <li></li>
                                    <li>
                                        <a href="https://daisyui.com/theme-generator/" class="btn btn-ghost w-full flex gap-4 px-2" target="_blank">
                                            <x-heroicon-o-paint-brush class="size-4 fill-current" />

                                            <div class="grow text-left text-sm font-bold">{{ __('make your own!') }}</div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </form>
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

                {{--- Mobile nav ---}}
                <aside class="flex flex-col md:hidden pl-4 pr-6 py-4 space-y-1 bg-base-300/90 text-base-content sticky top-0 z-30 h-16 w-full [transform:translate3d(0,0,0)] backdrop-blur transition-shadow duration-100 shadow-xs">
                    <div class="flex items-center space-x-2 justify-between">
                        <div class="flex items-center space-x-2">
                            <x-drawer.toggle for="main-sidebar" icon="bars-3" class="md:hidden px-2.5 [&>div>svg]:size-5! [&>div>svg]:mr-0! h-10" />

                            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                                <x-app-logo />
                            </a>
                        </div>

                        <ul class="flex items-center space-x-2">
                            <form action="{{ route('theme.update') }}" method="post" id="theme-form" data-action="submit->theme#updateFromSubmit">
                                @csrf
                                @method('PUT')

                                <button type="submit" class="sr-only">{{ __('Update Theme') }}</button>

                                <div class="dropdown dropdown-end">
                                    <x-navbar.item-button icon="paint-brush" class="px-2.5 [&>div>svg]:size-5! [&>div>svg]:mr-0! h-10 not-max-lg:hidden text-base-content/50">
                                        <span class="sr-only">{{ __('Theme') }}</span>
                                    </x-navbar.item-button>

                                    <ul tabindex="0" class="dropdown-content max-h-[50vh] [:where(&_li:empty)]:h-[0.1em] [:where(&_li:empty)]:bg-base-100/90 [:where(&_li:empty)]:my-2 [:where(&_li:empty)]:mx-1 overflow-y-auto bg-base-300 rounded-box z-1 w-52 p-2 shadow-2xl">
                                        <li class="menu-title text-xs">Theme</li>
                                        @foreach (['default', 'acid', 'autumn', 'aqua', 'bumblebee', 'business', 'caramellatte', 'cmyk', 'coffee', 'corporate', 'cupcake', 'cyberpunk', 'dark', 'dim', 'dracula', 'emerald', 'fantasy', 'forest', 'garden', 'halloween', 'light', 'lofi', 'luxury', 'night', 'nord', 'retro', 'silk', 'synthwave', 'valentine'] as $theme)
                                        <li>
                                            <button class="btn btn-ghost w-full flex gap-4 px-2" data-theme-target="button" type="submit" name="theme" value="{{ $theme }}">
                                                <div data-theme="{{ $theme }}" class="bg-base-100 grid shrink-0 grid-cols-2 gap-0.5 rounded-md p-1 shadow-sm">
                                                    <div class="bg-base-content size-1 rounded-full"></div>
                                                    <div class="bg-primary size-1 rounded-full"></div>
                                                    <div class="bg-secondary size-1 rounded-full"></div>
                                                    <div class="bg-accent size-1 rounded-full"></div>
                                                </div>

                                                <div class="w-32 truncate text-left">{{ $theme }}</div>

                                                <x-heroicon-o-check class="size-4 invisible" />
                                            </button>
                                        </li>
                                        @endforeach
                                        <li></li>
                                        <li>
                                            <a href="https://daisyui.com/theme-generator/" class="btn btn-ghost w-full flex gap-4 px-2" target="_blank">
                                                <x-heroicon-o-paint-brush class="size-4 fill-current" />

                                                <div class="grow text-left text-sm font-bold">{{ __('make your own!') }}</div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </form>

                            <x-navbar.item icon="magnifying-glass" href="#" class="px-2.5 [&>div>svg]:size-5! [&>div>svg]:mr-0! h-10 text-base-content/50">
                                <span class="sr-only">{{ __('Search') }}</span>
                            </x-navbar.item>

                            <a href="{{ route('settings') }}">
                                <x-profile :initials="auth()->user()->initials()" class="p-0!" />
                                <span class="sr-only">{{ auth()->user()->name }}</span>
                            </a>
                        </ul>
                    </div>
                </aside>

                <main class="flex-1 flex flex-col min-h-screen overflow-auto bg-base-200">
                    @include('partials.notifications')

                    <div class="overflow-auto flex-1 flex flex-col">
                        {{ $slot }}
                    </div>
                </main>
            </div>

            <x-slot name="aside">
                <div class="flex items-center space-x-2 z-10">
                    <x-drawer.toggle for="main-sidebar" icon="x-mark" class="md:hidden px-2 [&>div>svg]:size-5! [&>div>svg]:mr-0! h-10" />
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
