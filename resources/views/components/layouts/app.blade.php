<x-layouts.app.sidebar :title="$title ?? null">
    <main class="min-h-[90vh] flex flex-col h-full p-6 lg:p-8 w-full">
        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
            {{ $slot }}
        </div>
    </main>
</x-layouts.app.sidebar>
