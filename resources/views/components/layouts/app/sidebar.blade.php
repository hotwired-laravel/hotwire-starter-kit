@props(['transitions' => true, 'scalable' => false, 'title' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head', [
            'transitions' => $transitions,
            'scalable' => $scalable,
            'title' => $title,
        ])
    </head>
    <body data-controller="sidebar theme" data-layout="sidebar" data-theme-active-class="btn-active [&_svg]:visible!" data-action="turbo:before-cache@document->sidebar#close" @class(["min-h-screen antialiased bg-base-300", "hotwire-native" => Turbo::isHotwireNativeVisit()])>
        {{-- If you don't want the collapsible behavior, just remove the data-collapsible attribute here... --}}
        <x-sidebar id="main-sidebar" data-collapsible class="min-h-screen">
            <x-slot name="aside">
                <div class="flex items-center justify-between group/sidebar-header">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group-has-[:checked]/sidebar:lg:group-hover/sidebar-header:hidden">
                        <x-app-logo />
                    </a>

                    <x-sidebar.collapse for="main-sidebar" class="group-has-[:checked]/sidebar:lg:hidden! group-has-[:checked]/sidebar:lg:group-hover/sidebar-header:inline-flex!" />
                </div>

                <div class="space-y-2 mt-4">
                    <span class="text-base-content/50 text-xs px-1 group-has-[:checked]/sidebar:lg:hidden">{{ __('Platform') }}</span>

                    <x-sidebar.navlist class="px-0">
                        <x-sidebar.navlist-item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-sidebar.navlist-item>
                    </x-sidebar.navlist>
                </div>

                <div class="flex-1"></div>

                <x-sidebar.navlist class="px-0">
                    <x-sidebar.navlist-item icon="code-bracket" target="_blank" href="https://github.com/hotwired-laravel/hotwire-starter-kit">{{ __('Repository') }}</x-sidebar.navlist-item>
                    <x-sidebar.navlist-item icon="book-open" target="_blank" href="https://turbo-laravel.com">{{ __('Documentation') }}</x-sidebar.navlist-item>

                    <div class="dropdown dropdown-top dropdown-right w-full">
                        <x-sidebar.navlist-item icon="paint-brush" as="button" type="button" class="w-full">
                            <span class="flex-1 flex items-center justify-between gap-2">
                                <span>{{ __('Theme') }}</span>
                                <x-heroicon-o-chevron-up-down class="size-4" />
                            </span>
                        </x-sidebar.navlist-item>

                        <ul tabindex="0" data-theme-target="switcher" class="dropdown-content max-h-[50vh] [:where(&_li:empty)]:h-[0.1em] [:where(&_li:empty)]:bg-base-100/90 [:where(&_li:empty)]:my-2 [:where(&_li:empty)]:mx-1 overflow-y-auto bg-base-300 rounded-box z-1 w-52 p-2 shadow-2xl">
                            <li class="menu-title text-xs">Theme</li>
                            <template data-theme-target="template">
                                <li>
                                    <button class="btn btn-ghost w-full flex gap-4 px-2" data-theme-target="button" type="button" data-action="theme#update">
                                        <div data-theme-placeholder class="bg-base-100 grid shrink-0 grid-cols-2 gap-0.5 rounded-md p-1 shadow-sm">
                                            <div class="bg-base-content size-1 rounded-full"></div>
                                            <div class="bg-primary size-1 rounded-full"></div>
                                            <div class="bg-secondary size-1 rounded-full"></div>
                                            <div class="bg-accent size-1 rounded-full"></div>
                                        </div>

                                        <div data-label class="w-32 truncate text-left"></div>

                                        <x-heroicon-o-check class="size-4 invisible" />
                                    </button>
                                </li>
                            </template>
                            <li></li>
                            <li>
                                <a href="https://daisyui.com/theme-generator/" class="btn btn-ghost p-0! w-full flex gap-4 px-2" target="_blank">
                                    <x-heroicon-o-paint-brush class="size-4 fill-current" />

                                    <div class="grow text-left text-sm font-bold">{{ __('make your own!') }}</div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </x-sidebar.navlist>

                <x-sidebar.navlist class="px-0">
                    <x-sidebar.navlist-item :href="route('settings')" :current="request()->routeIs(['settings', 'settings.*'])">
                        <x-slot name="iconSection">
                            <x-profile :initials="auth()->user()->initials()" class="p-0! hover:bg-transparent!" />
                        </x-slot>
                        {{ auth()->user()->name }}
                    </x-sidebar.navlist-item>
                </x-sidebar.navlist>
            </x-slot>

            <x-slot name="header">
                {{-- Mobile nav --}}
                <header class="flex items-center lg:hidden min-h-14 z-10 sticky top-0 border-b border-black/10 dark:border-white/5 bg-base-300/90 [transform:translate3d(0,0,0)] backdrop-blur transition-shadow duration-100 shadow-xs">
                    <div class="flex items-center px-4 w-full justify-between">
                        <div class="flex items-center space-x-2">
                            <x-drawer.toggle for="main-sidebar" icon="bars-3" class="lg:hidden px-2.5 [&>div>svg]:size-5! [&>div>svg]:mr-0! h-10" />

                            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                                <x-app-logo />
                            </a>
                        </div>

                        <ul class="flex items-center space-x-2">
                            <div class="dropdown dropdown-end">
                                <x-navbar.item-button icon="paint-brush" class="px-2.5 [&>div>svg]:size-5! [&>div>svg]:mr-0! h-10 not-max-lg:hidden text-base-content/50">
                                    <span class="sr-only">{{ __('Theme') }}</span>
                                </x-navbar.item-button>

                                <ul tabindex="0" data-theme-target="switcher" class="dropdown-content max-h-[50vh] [:where(&_li:empty)]:h-[0.1em] [:where(&_li:empty)]:bg-base-100/90 [:where(&_li:empty)]:my-2 [:where(&_li:empty)]:mx-1 overflow-y-auto bg-base-300 rounded-box z-1 w-52 p-2 shadow-2xl">
                                    <li class="menu-title text-xs">Theme</li>
                                    <template data-theme-target="template">
                                        <li>
                                            <button class="btn btn-ghost w-full flex gap-4 px-2" data-theme-target="button" type="button" data-action="theme#update">
                                                <div data-theme-placeholder class="bg-base-100 grid shrink-0 grid-cols-2 gap-0.5 rounded-md p-1 shadow-sm">
                                                    <div class="bg-base-content size-1 rounded-full"></div>
                                                    <div class="bg-primary size-1 rounded-full"></div>
                                                    <div class="bg-secondary size-1 rounded-full"></div>
                                                    <div class="bg-accent size-1 rounded-full"></div>
                                                </div>

                                                <div data-label class="w-32 truncate text-left"></div>

                                                <x-heroicon-o-check class="size-4 invisible" />
                                            </button>
                                        </li>
                                    </template>
                                    <li></li>
                                    <li>
                                        <a href="https://daisyui.com/theme-generator/" class="btn btn-ghost w-full flex gap-4 px-2" target="_blank">
                                            <x-heroicon-o-paint-brush class="size-4 fill-current" />

                                            <div class="grow text-left text-sm font-bold">{{ __('make your own!') }}</div>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <x-navbar.item icon="magnifying-glass" href="#" class="px-2.5 [&>div>svg]:size-5! [&>div>svg]:mr-0! h-10 text-base-content/50">
                                <span class="sr-only">{{ __('Search') }}</span>
                            </x-navbar.item>

                            <a href="{{ route('settings') }}">
                                <x-profile :initials="auth()->user()->initials()" class="p-0!" />
                                <span class="sr-only">{{ auth()->user()->name }}</span>
                            </a>
                        </ul>
                    </div>
                </header>
            </x-slot>

            @include('partials.notifications')

            {{ $slot }}
        </x-sidebar>
    </body>
</html>
