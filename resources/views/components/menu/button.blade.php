@props(['icon' => null, 'type' => 'submit'])

<button {{ $attributes->merge(['type' => $type, 'class' => 'col-span-2 grid grid-cols-subgrid items-center rounded-lg px-3 py-1.5 text-sm/6 [--icon-color:var(--color-base-200)] hover:bg-base-300 hover:text-base-900 hover:[--icon-color:var(--color-white)]']) }}>
    @if ($icon ?? false)
    <x-dynamic-component :component="'icons.'.$icon" />
    @endif

    <span class="col-start-2 text-left cursor-pointer whitespace-nowrap">{{ $slot }}</span>
</button>
