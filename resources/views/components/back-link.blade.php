<div class="flex items-center justify-center mb-2">
    <a {{ $attributes->merge(['class' => 'btn btn-link']) }}>
        <x-icons.arrow-return-left size="md" />

        {{ $slot }}
    </a>
</div>
