<div class="flex items-center justify-center mb-2">
    <a {{ $attributes->merge(['class' => 'btn btn-link']) }}>
        <x-heroicon-o-arrow-left class="size-4" />

        {{ $slot }}
    </a>
</div>
