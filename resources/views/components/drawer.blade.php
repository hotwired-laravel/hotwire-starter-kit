@props(['id', 'asideWidth' => 'w-64'])

<div class="drawer">
    @if ($checkbox ?? false)
        {{ $checkbox }}
    @else
        <input id="{{ $id }}" type="checkbox" class="drawer-toggle" />
    @endif

    <div class="drawer-content">
        {{ $slot }}
    </div>

    <div class="drawer-side">
        <label for="{{ $id }}" aria-label="close sidebar" class="drawer-overlay"></label>

        <div class="bg-base-200 text-base-content min-h-full {{ $asideWidth }} p-4 flex flex-col">
            {{ $aside }}
        </div>
    </div>
</div>
