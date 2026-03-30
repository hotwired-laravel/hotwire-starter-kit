@props(['id'])

<div {{ $attributes->merge(['class' => 'drawer lg:drawer-open']) }}>
    {{-- Mobile drawer toggle --}}
    <input id="{{ $id }}" data-sidebar-target="checkbox" type="checkbox" class="drawer-toggle" />

    {{-- Main content area --}}
    <div class="drawer-content flex flex-col min-h-screen lg:h-screen">
        {{ $header ?? '' }}

        <main class="flex-1 flex flex-col lg:overflow-auto bg-base-200">
            {{ $slot }}
        </main>
    </div>

    {{-- Sidebar --}}
    <div class="drawer-side z-40 lg:overflow-visible!">
        <label for="{{ $id }}" class="drawer-overlay"></label>

        <aside @class([
            'group/sidebar flex flex-col bg-base-300 text-base-content min-h-full border-r border-black/10 dark:border-white/5',
            'w-[250px] p-4 space-y-1',
            'data-[collapsible]:has-[:checked]:lg:w-16 data-[collapsible]:has-[:checked]:lg:px-2 data-[collapsible]:has-[:checked]:lg:items-center data-[collapsible]:lg:transition-all data-[collapsible]:lg:duration-200 data-[collapsible]:lg:ease-in-out',
        ]) @if($attributes->has('data-collapsible')) data-collapsible @endif>
            @if($attributes->has('data-collapsible'))
                <input type="checkbox" id="{{ $id }}-collapse" data-sidebar-target="collapse" class="sr-only" />
            @endif

            {{ $aside }}
        </aside>
    </div>
</div>
