<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-box border border-neutral/10 dark:border-neutral/80">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-neutral/30 dark:stroke-neutral/90" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-box border border-neutral/10 dark:border-neutral/80">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-neutral/30 dark:stroke-neutral/90" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-box border border-neutral/10 dark:border-neutral/80">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-neutral/30 dark:stroke-neutral/90" />
            </div>
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-box border border-neutral/10 dark:border-neutral/80">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-neutral/30 dark:stroke-neutral/90" />
        </div>
    </div>
</x-layouts.app>
