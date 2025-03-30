<div {{ $attributes->merge(['class' => 'drawer']) }}>
    <input id="sidebar-toggle" data-sidebar-target="checkbox" type="checkbox" class="drawer-toggle" />

    <div class="drawer-side">
        <label for="sidebar-toggle" aria-label="{{ __('Close sidebar') }}" class="drawer-overlay"></label>

        <div class="bg-base-200 text-base-content min-h-full w-64 p-4 flex flex-col">
            <div>
                <x-sidebar.toggle class="lg:hidden ml-0" icon="x-mark" />
            </div>

            {{ $side }}
        </div>
    </div>
</div>
