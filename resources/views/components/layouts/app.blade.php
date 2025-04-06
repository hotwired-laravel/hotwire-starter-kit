<x-layouts.app.header :title="$title ?? null">
    <main class="min-h-[90vh] flex flex-col h-full p-6 lg:p-8 max-w-7xl mx-auto w-full">
        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
            {{ $slot }}
        </div>
    </main>
</x-layouts.app.header>
